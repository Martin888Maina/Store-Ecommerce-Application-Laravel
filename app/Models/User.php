<?php
//namespace
namespace App\Models;
//interface to verify the email
use Illuminate\Contracts\Auth\MustVerifyEmail;
//imports the  factory for seeding database with dummy data
use Illuminate\Database\Eloquent\Factories\HasFactory;
//imports authenticable class for login and password hashing
use Illuminate\Foundation\Auth\User as Authenticatable;
//supports notifications
use Illuminate\Notifications\Notifiable;
//imports sanctum that hadles authentication
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    //traits
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'store_data', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // One-to-one relationship between the user and cart tables
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // One-to-many relationship between the user and orders tables
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
