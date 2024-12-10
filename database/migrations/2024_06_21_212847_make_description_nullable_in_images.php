<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('images', function (Blueprint $table) {
      $table->string('description')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    DB::table('images')
      ->whereNull('description')
      ->update(['description' => 'No Description']);

    Schema::table('images', function (Blueprint $table) {
      $table->string('description')->change();
    });
  }
};
