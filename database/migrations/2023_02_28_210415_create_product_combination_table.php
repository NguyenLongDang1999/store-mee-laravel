<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_combination', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->float('price')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('gallery_id')->nullable();
        });

        Schema::table('product_combination', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('gallery_id')->references('id')->on('product_gallery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_combination');
    }
};
