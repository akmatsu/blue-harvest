<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteFileJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $filePath;

  public function __construct($filePath)
  {
    $this->filePath = $filePath;
  }

  public function handle()
  {
    Log::info("Starting to delete file: {$this->filePath}");
    if (Storage::delete($this->filePath)) {
      Log::info("File deleted successfully: {$this->filePath}");
    } else {
      Log::error("Failed to delete file: {$this->filePath}");
    }
  }
}
