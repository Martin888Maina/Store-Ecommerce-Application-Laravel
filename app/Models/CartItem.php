<?php

namespace App\Models;
//imports the  factory for seeding database with dummy data
use Illuminate\Database\Eloquent\Factories\HasFactory;
//imports the laravel eloquent model 
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //trait
    use HasFactory;

    //prevents against attacks involving mass assignment of data
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    // Many to Many relationship between the Cart and Product tables
    // This is a joining table
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Many to Many relationship between the Cart and Product tables
    // This is a joining table
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
