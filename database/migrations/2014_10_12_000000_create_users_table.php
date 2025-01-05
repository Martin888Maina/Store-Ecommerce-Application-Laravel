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
        Schema::create('users', function (Blueprint $table) {
            // id for the users table
            $table->id();
            //name of the user
            $table->string('name');
            //email of the user - unique cannot be repeated
            $table->string('email')->unique();
            //email verification
            $table->timestamp('email_verified_at')->nullable();
            //password of the user
            $table->string('password');
            //cart data of the user stored before registration
            $table->json('store_data')->nullable();
            //remember me token
            $table->rememberToken();
            // time and date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
