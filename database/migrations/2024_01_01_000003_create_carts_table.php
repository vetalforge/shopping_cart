<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->uuid('cart_token')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('carts');
    }
};
