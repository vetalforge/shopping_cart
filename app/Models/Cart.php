<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'cart_token'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
