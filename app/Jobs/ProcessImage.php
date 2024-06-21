<?php

namespace App\Jobs;

use App\Models\Flag;
use App\Models\Image;
use App\Models\Tag;
use App\Notifications\ImageAutoFlagNotification;
use App\Notifications\ImageProcessedNotification;
use App\Notifications\ImageProcessFailedNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProcessImage implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $tries = 3;

  /**
   * The number of times the job may be attempted.
   *
   * @var int
   */
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
    $dbImage->status = 'processing';
    $dbImage->save();

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
      $dbImage->status = 'public';
      $dbImage->save();
    } else {
      $dbImage->status = 'pending review';
      Flag::create([
        'flaggable_id' => $dbImage->id,
        'flaggable_type' => 'App\Models\Image',
        'reason' => 'This image was automatically flagged by the AI',
      ]);
      $dbImage->save();
    }

    $dbImage->tags()->syncWithoutDetaching($tagIds);

    if (!$resFlag) {
      $dbImage->user->notify(new ImageProcessedNotification($dbImage));
    } else {
      $dbImage->user->notify(new ImageAutoFlagNotification($dbImage));
    }
  }

  public function failed(Exception $exc): void
  {
    $image = Image::findOrFail($this->dbImageId);
    $image->status = 'unprocessed';
    $image->save();
    $image->user->notify(new ImageProcessFailedNotification($image));
  }
}
