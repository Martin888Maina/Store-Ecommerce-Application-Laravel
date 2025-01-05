<?php

//imports the migration class from laravel
use Illuminate\Database\Migrations\Migration;
//used to defne the structure of a database table
use Illuminate\Database\Schema\Blueprint;
//interacts with the database tables
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     */

     // the create orderItems table allows us to track the users, the products they selected and the various quantities and prices that they are checking out.
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            //id of the record in the order items table
            $table->id();
            //foreign key - relationship between order table with the order items table
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            //foreign key - relationship between product table and the order items table
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            //quantity of the product in the order items table
            $table->integer('quantity');
            //price of the product in the order items table
            $table->decimal('price', 10, 2); 
            //timestamp of the record
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
