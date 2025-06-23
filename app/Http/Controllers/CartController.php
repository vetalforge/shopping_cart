<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    /**
     * @var CartService
     */
    protected $cart;

    /**
     * CartController constructor.
     * @param CartService $cart
     */
    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartItems(Request $request)
    {
        $items = $this->cart->getCartItems($request->cookie('cart_token'));

        return response()->json($items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'title' => $item->product->title,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ];
        }));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->cart->addToCart($request->cookie('cart_token'), $data['product_id'], $data['quantity']);

        return response()->json(['success' => true, 'cart_token' => $cart->cart_token]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cart->updateCartItem($request->cookie('cart_token'), $id, $data['quantity']);

        return response()->json(['success' => true]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request, $id)
    {
        $this->cart->removeCartItem($request->cookie('cart_token'), $id);

        return response()->json(['success' => true]);
    }
}
