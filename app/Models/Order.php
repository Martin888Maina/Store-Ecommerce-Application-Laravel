<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    //prevents against attacks involving mass assignment of data
    protected $fillable = [
        'user_id',
        'total_price',
    ];

    // one user can have many orders
        public function user()
    {
        return $this->belongsTo(User::class);
    }
  
    // one order can have many order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship: One order can have one payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
