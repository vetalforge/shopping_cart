<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition()
    {
        return [
            'cart_id' => Cart::factory(),       // створює новий кошик, якщо не вказано явно
            'product_id' => Product::factory(), // створює новий продукт
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
