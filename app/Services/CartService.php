<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    /**
     * @param string $cartToken
     * @return mixed
     */
    public function getOrCreateCart(string $cartToken)
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        return Cart::firstOrCreate(['cart_token' => $cartToken]);
    }


    /**
     * @param string $cartToken
     * @return mixed
     */
    public function getCartItems(string $cartToken)
    {
        $cart = $this->getOrCreateCart($cartToken);

        return $cart->items()->with('product')->get();
    }

    /**
     * @param string $cartToken
     * @param int $productId
     * @param int $quantity
     * @return mixed
     */
    public function addToCart(string $cartToken, int $productId, int $quantity)
    {
        $cart = $this->getOrCreateCart($cartToken);

        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $cart;
    }

    /**
     * @param string $cartToken
     * @param int $productId
     * @param int $quantity
     */
    public function updateCartItem(string $cartToken, int $productId, int $quantity): void
    {
        $cart = $this->getOrCreateCart($cartToken);
        $item = $cart->items()->where('product_id', $productId)->firstOrFail();
        $item->quantity = $quantity;
        $item->save();
    }

    /**
     * @param string $cartToken
     * @param int $productId
     */
    public function removeCartItem(string $cartToken, int $productId): void
    {
        $cart = $this->getOrCreateCart($cartToken);
        $cart->items()->where('product_id', $productId)->delete();
    }

    /**
     * @param string $cartToken
     * @param int $userId
     */
    public function assignCartToUser(string $cartToken, int $userId): void
    {
        $cart = Cart::where('cart_token', $cartToken)->first();

        if ($cart && !$cart->user_id) {
            $existingUserCart = Cart::where('user_id', $userId)->first();

            if ($existingUserCart) {
                foreach ($cart->items as $item) {
                    $existingUserCart->items()->updateOrCreate(
                        ['product_id' => $item->product_id],
                        ['quantity' => $item->quantity]
                    );
                }

                $cart->delete();
            } else {
                $cart->user_id = $userId;
                $cart->save();
            }
        }
    }
}
