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
        Schema::create('variation', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('attribute_id');
        });

        Schema::table('variation', function (Blueprint $table) {
            $table->foreign('attribute_id')->references('id')->on('attribute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variation');
    }
};
