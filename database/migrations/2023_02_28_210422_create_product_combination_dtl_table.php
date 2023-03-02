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
        Schema::create('product_combination_dtl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('product_combination_id')->nullable();
        });

        Schema::table('product_combination_dtl', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('variation_id')->references('id')->on('variation');
            $table->foreign('product_combination_id')->references('id')->on('product_combination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_combination_dtl');
    }
};
