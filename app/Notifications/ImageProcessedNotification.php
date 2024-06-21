<?php

namespace App\Notifications;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ImageProcessedNotification extends Notification implements ShouldQueue
{
  use Queueable;

  protected $image;

  /**
   * Create a new notification instance.
   */
  public function __construct(Image $image)
  {
    $this->image = $image;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['database', 'broadcast'];
  }

  public function toBroadcast(object $notifiable)
  {
    return new BroadcastMessage([
      'image_id' => $this->image->id,
      'message' => 'Your image has been processed.',
    ]);
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    return [
      'image_id' => $this->image->id,
      'message' => 'Your Image has been processed.',
    ];
  }

  public function toDatabase($notifiable)
  {
    return [
      'image_id' => $this->image->id,
      'message' => 'Your image has been processed.',
    ];
  }
}
