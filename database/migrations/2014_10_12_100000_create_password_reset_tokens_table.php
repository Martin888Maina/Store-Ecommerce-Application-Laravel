<?php

//imports the migration class from laravel
use Illuminate\Database\Migrations\Migration;
//used to defne the structure of a database table
use Illuminate\Database\Schema\Blueprint;
//interacts with the database tables
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            //email of the user
            $table->string('email')->primary();
            //token containing users coded data
            $table->string('token');
            //cart data
            $table->json('cart_data')->nullable();
            // time and data record was created
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
