<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $cartToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Імітуємо, що у клієнта є cookie з cart_token
        $this->cartToken = (string) \Illuminate\Support\Str::uuid();

        // Ініціалізуємо порожню корзину з цим токеном, щоб не створювати зайвих проблем
        Cart::factory()->create(['cart_token' => $this->cartToken]);
    }

    public function test_get_cart_items_returns_empty_array()
    {
        $response = $this->withCookie('cart_token', $this->cartToken)
            ->getJson('/cart');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_add_product_to_cart()
    {
        $product = Product::factory()->create();

        $response = $this->withCookie('cart_token', $this->cartToken)
            ->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 3,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'cart_token' => $this->cartToken,
            ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }

    public function test_update_cart_item_quantity()
    {
        $product = Product::factory()->create();

        // Спершу додамо товар у кошик
        $this->withCookie('cart_token', $this->cartToken)
            ->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        // Оновимо кількість товару в кошику (за id продукту)
        $response = $this->withCookie('cart_token', $this->cartToken)
            ->putJson("/cart/update/{$product->id}", [
                'quantity' => 5,
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
    }

    public function test_remove_cart_item()
    {
        $product = Product::factory()->create();

        $this->withCookie('cart_token', $this->cartToken)
            ->postJson('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

        $response = $this->withCookie('cart_token', $this->cartToken)
            ->deleteJson("/cart/remove/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $product->id,
        ]);
    }
}
