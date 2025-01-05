<?php

//imports the migration class from laravel
use Illuminate\Database\Migrations\Migration;
//used to defne the structure of a database table
use Illuminate\Database\Schema\Blueprint;
//interacts with the database tables
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     */

     // The create cartItems table allows us to track the users, the products they selected and the various quantities that they have added to the cart
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            //id of the record in the cart items table
            $table->id();
            // foreign key - relationship between the cart table and the cart items table
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            // foreign key - relatiomship between the product table and the cart items table
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // quantity of the product in the cart items table
            $table->integer('quantity');
            // timestamp of the record in the cart items table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
