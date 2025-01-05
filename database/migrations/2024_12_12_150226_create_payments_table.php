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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Links payment to an order
            $table->string('transaction_reference')->unique(); // Reference ID from Quickteller
            $table->decimal('amount', 10, 2); // Payment amount
            $table->string('status')->default('pending'); // pending, successful, failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
