<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    //prevents against attacks involving mass assignment of data
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];


    // Many to Many relationship between the OrderItem table and Ordertables
    // one order can have many order items
    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    // order Items belong to a product
    // a product can have many order items
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
