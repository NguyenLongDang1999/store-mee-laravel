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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price')->default(0);
            $table->decimal('price_discount')->default(0);
            $table->integer('type_discount')->default(0);
            $table->string('image_uri')->nullable();
            $table->longText('content')->nullable();
            $table->string('description')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('view')->default(0);
            $table->smallInteger('status')->default(0);
            $table->smallInteger('popular')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('product', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brand');
            $table->foreign('category_id')->references('id')->on('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
