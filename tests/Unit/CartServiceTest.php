<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService();
    }

    public function test_guest_cart_creation_and_item_addition()
    {
        $cartToken = (string) Str::uuid();
        $product = Product::factory()->create();

        $this->cartService->addToCart($cartToken, $product->id, 2);

        $cart = Cart::where('cart_token', $cartToken)->first();

        $this->assertNotNull($cart);
        $this->assertEquals(1, $cart->items()->count());
        $this->assertEquals(2, $cart->items()->first()->quantity);
    }

    public function test_authenticated_cart_creation()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $cart = $this->cartService->getOrCreateCart('any-token');

        $this->assertEquals($user->id, $cart->user_id);
    }

    public function test_update_cart_item_quantity()
    {
        $cartToken = (string) Str::uuid();
        $product = Product::factory()->create();

        $this->cartService->addToCart($cartToken, $product->id, 1);
        $this->cartService->updateCartItem($cartToken, $product->id, 5);

        $item = CartItem::first();
        $this->assertEquals(5, $item->quantity);
    }

    public function test_remove_cart_item()
    {
        $cartToken = (string) Str::uuid();
        $product = Product::factory()->create();

        $this->cartService->addToCart($cartToken, $product->id, 1);
        $this->cartService->removeCartItem($cartToken, $product->id);

        $this->assertEquals(0, CartItem::count());
    }

    public function test_assign_cart_to_user_merges_items()
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $guestToken = (string) Str::uuid();
        $this->cartService->addToCart($guestToken, $product1->id, 1);

        $user = User::factory()->create();
        Auth::login($user);
        $this->cartService->addToCart('any-token', $product2->id, 2);

        $this->cartService->assignCartToUser($guestToken, $user->id);

        $this->assertEquals(2, Cart::where('user_id', $user->id)->first()->items()->count());
    }
}
