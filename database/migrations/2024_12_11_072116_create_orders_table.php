<?php

//imports the migration class from laravel
use Illuminate\Database\Migrations\Migration;
//used to defne the structure of a database table
use Illuminate\Database\Schema\Blueprint;
//interacts with the database tables
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            //id of the record in the orders table
            $table->id();
            // foreign key - relationship betweenn the order table and the users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            //price of the product
            $table->decimal('total_price', 10, 2);
            //timestamp in the orders table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
