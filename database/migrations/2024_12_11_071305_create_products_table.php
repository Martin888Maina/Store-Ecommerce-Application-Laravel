<?php
//imports the migration class from laravel
use Illuminate\Database\Migrations\Migration;
//used to defne the structure of a database table
use Illuminate\Database\Schema\Blueprint;
//interacts with the database tables
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            //id of the product in the database
            $table->id();
            //name of the product in the database
            $table->string('name');
            //description of the product in the database
            $table->text('description');
            //price of the product in the database
            $table->decimal('price', 10, 2);
            //image of the product in the database
            $table->string('image')->nullable();
            //timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
