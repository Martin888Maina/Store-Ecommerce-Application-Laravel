<?php
//namaespace
namespace App\Models;
//imports the  factory for seeding database with dummy data
use Illuminate\Database\Eloquent\Factories\HasFactory;
//imports the laravel eloquent model
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //trait
    use HasFactory;

    //prevents against attacks involving mass assignment of data
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    // one to Many relationship between Cart and Products table
    // One cart has many products
        public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // one to one relationship between Cart and User
    // one cart has one user
        public function user()
    {
        return $this->belongsTo(User::class);
    }

    // One-to-many relationship between Cart and CartItem
        public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }


}
