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
    Schema::table('images', function (Blueprint $table) {
      $table
        ->enum('status', [
          'unprocessed',
          'processing',
          'pending review',
          'public',
          'private',
        ])
        ->default('unprocessed')
        ->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('images', function (Blueprint $table) {
      $table
        ->enum('status', ['unprocessed', 'processing', 'public', 'private'])
        ->default('unprocessed');
      $table->string('description')->change();
    });
  }
};
