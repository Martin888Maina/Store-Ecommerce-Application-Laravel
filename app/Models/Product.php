<?php
//namespace
namespace App\Models;
//imports the  factory for seeding database with dummy data
use Illuminate\Database\Eloquent\Factories\HasFactory;
//imports the laravel eloquent model 
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //trait
    use HasFactory;

        //prevents against attacks involving mass assignment of data
        protected $fillable = [
            'name',
            'description',
            'price',
            'image',
        ];

        // Many to Many relationship between the Cart and Product tables
        // Many products can have many Cart Items
            public function cartItems()
        {
            return $this->hasMany(CartItem::class);
        }

        // Many to Many relationship between the Order and Product tables
        // Many products can have many Order Items
        public function orderItems()
        {
            return $this->hasMany(OrderItem::class);
        }

}
