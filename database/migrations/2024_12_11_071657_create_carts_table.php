<?php

//imports the migration class from laravel
use Illuminate\Database\Migrations\Migration;
//used to defne the structure of a database table
use Illuminate\Database\Schema\Blueprint;
//interacts with the database tables
use Illuminate\Support\Facades\Schema;


class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            //id of the record in the carts table
            $table->id();
            // foreign key - relationship between the cart and user table in the database
            // Make user_id nullable to allow guest users without a user_id
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            // implemented sessions to track the activity of guest users who are not logged in
            $table->string('session_id')->nullable();
            // timestamp of the record
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
