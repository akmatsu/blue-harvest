<?php

namespace App\Notifications;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ImageAutoFlagNotification extends Notification implements ShouldQueue
{
  use Queueable;

  protected $image;
  protected $message = 'Your image was processed but was auto flagged and is pending Admin review.';

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
      'message' => $this->message,
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
      'message' => $this->message,
    ];
  }

  public function toDatabase($notifiable)
  {
    return [
      'image_id' => $this->image->id,
      'message' => $this->message,
    ];
  }
}
