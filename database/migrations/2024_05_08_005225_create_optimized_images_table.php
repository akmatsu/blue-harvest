<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('optimized_images', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->unsignedBigInteger('image_id');
      $table->unsignedBigInteger('file_size');
      $table->string('path');
      $table->string('url');
      $table->string('size'); // 'e.g.,' small, medium, large
      $table->integer('width');
      $table->integer('height');

      $table
        ->foreign('image_id')
        ->references('id')
        ->on('images')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('optimized_images');
  }
};
