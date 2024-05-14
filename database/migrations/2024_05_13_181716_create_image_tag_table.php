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
    Schema::create('image_tag', function (Blueprint $table) {
      $table->foreignId('image_id')->constrained()->onDelete('cascade');
      $table->foreignId('tag_id')->constrained()->onDelete('cascade');
      $table->primary(['image_id', 'tag_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('image_tag');
  }
};
