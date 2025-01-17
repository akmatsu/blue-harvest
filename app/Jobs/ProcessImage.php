<?php

namespace App\Jobs;

use App\Models\Flag;
use App\Models\Image;
use App\Models\Tag;
use App\Notifications\ImageAutoFlagNotification;
use App\Notifications\ImageProcessedNotification;
use App\Notifications\ImageProcessFailedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProcessImage implements ShouldQueue, ShouldBeUnique
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * The number of times the job may be attempted.
   *
   * @var int
   */
  public $tries = 3;

  protected Image $dbImage;
  protected $filePath;
  protected $fileClientOriginalName;

  /**
   * Create a new job instance.
   */
  public function __construct(Image $img, $file)
  {
    $this->dbImage = $img;
    $this->fileClientOriginalName = $file->getClientOriginalName();
    $this->onQueue('processImages');
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $this->dbImage->status = 'processing';
    $this->dbImage->save();

    $contents = Storage::get($this->dbImage->path);

    $res = Http::attach('file', $contents, $this->fileClientOriginalName)
      ->timeout(180)
      ->post(config('services.clip.url'));

    $resJson = $res->json();
    if (!isset($resJson['tags'], $resJson['flag'])) {
      $this->failed();
      return;
    }
    $resTags = $resJson['tags'];
    $resFlag = $resJson['flag'];

    $tagIds = [];
    foreach ($resTags as $tagName) {
      $tag = Tag::firstOrCreate(['name' => $tagName]);
      $tagIds[] = $tag->id;
    }

    if (!$resFlag) {
      $this->dbImage->status = 'public';
    } else {
      $this->dbImage->status = 'pending review';
      Flag::create([
        'flaggable_id' => $this->dbImage->id,
        'flaggable_type' => 'App\Models\Image',
        'reason' => 'This image was automatically flagged by the AI',
      ]);
    }

    $this->dbImage->tags()->syncWithoutDetaching($tagIds);
    $this->dbImage->save();

    if (!$resFlag) {
      $this->dbImage->user->notify(
        new ImageProcessedNotification($this->dbImage)
      );
    } else {
      $this->dbImage->user->notify(
        new ImageAutoFlagNotification($this->dbImage)
      );
    }
  }

  public function failed(): void
  {
    $image = $this->dbImage;
    $image->status = 'unprocessed';
    $image->save();
    Flag::create([
      'flaggable_id' => $image->id,
      'flaggable_type' => 'App\Models\Image',
      'reason' => 'This image could not be processed by the AI',
    ]);
    $image->user->notify(new ImageProcessFailedNotification($image));
  }
}
