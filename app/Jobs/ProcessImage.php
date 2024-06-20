<?php

namespace App\Jobs;

use App\Models\Flag;
use App\Models\Image;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessImage implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $dbImageId;
  protected $filePath;
  protected $fileClientOriginalName;

  /**
   * Create a new job instance.
   */
  public function __construct($dbImage, $file)
  {
    $this->dbImageId = $dbImage->id;
    $this->fileClientOriginalName = $file->getClientOriginalName();
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $dbImage = Image::findOrFail($this->dbImageId);
    $contents = Storage::get($dbImage->path);

    $res = Http::attach('file', $contents, $this->fileClientOriginalName)
      ->timeout(180)
      ->post(config('services.clip.url'));

    $resJson = $res->json();
    $resTags = $resJson['tags'];
    $resFlag = $resJson['flag'];

    $tagIds = [];
    foreach ($resTags as $tagName) {
      $tag = Tag::firstOrCreate(['name' => $tagName]);
      $tagIds[] = $tag->id;
    }

    if (!$resFlag) {
      Log::info('Not flagging');
      $dbImage->is_published = true;
      $dbImage->save();
    } else {
      Log::info('Flagging');
      $dbImage->is_published = false;
      Flag::create([
        'flaggable_id' => $dbImage->id,
        'flaggable_type' => 'App\Models\Image',
        'reason' => 'This image was automatically flagged by the AI',
      ]);
      $dbImage->save();
    }

    $dbImage->tags()->syncWithoutDetaching($tagIds);
  }
}
