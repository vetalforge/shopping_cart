<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'title' => 'Apples',
                'description' => 'Here are some delicious apples!',
                'price' => 100.00,
                'image' => 'https://assets.clevelandclinic.org/m/5846d1f42f48ff09/webimage-Apples-184940975-770x533-1_jpg.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'title' => 'Banana',
                'description' => 'Bananaman!',
                'price' => 200.00,
                'image' => 'https://exoticfruits.co.uk/cdn/shop/products/banana-apple-manzano-exoticfruitscouk-905674.jpg?v=1749566721',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'title' => 'Chocolate',
                'description' => 'Cause it\'s the best thing ever created!',
                'price' => 39.00,
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Milka_Alpine_Milk_Chocolate_bar_100g_with_chunks_broken_off.jpg/1200px-Milka_Alpine_Milk_Chocolate_bar_100g_with_chunks_broken_off.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'title' => 'Strawberries',
                'description' => 'It\'s so very delicious!',
                'price' => 45.00,
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSB4vrbcT6l4XGdmWFB4AX7oVwid8fNNwjKpQ&s',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'title' => 'Cactus Needle',
                'description' => 'Every needle is worht it!',
                'price' => 1.00,
                'image' => 'https://www.shutterstock.com/image-photo/cactus-needles-closeup-green-succulent-600nw-2279999733.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
