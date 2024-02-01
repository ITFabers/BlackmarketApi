<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
      $user = auth()->user();
      $notifications = $user->notifications;

      return view('admin.notifications', compact('notifications'));
    }
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read_at' => now()]);

        return redirect()->back();
    }
}
