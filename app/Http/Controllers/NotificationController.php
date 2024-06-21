<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
  public function unread(Request $request)
  {
    return $request->user()->unreadNotifications;
  }

  public function read(string $id, Request $request)
  {
    $notification = $request->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    return $request->user()->unreadNotification;
  }
}
