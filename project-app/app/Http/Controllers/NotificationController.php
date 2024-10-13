<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
        ->where('is_deleted', 0)
        ->with('authorizedUser')  // Eager load the authorized user
        ->orderBy('created_at', 'desc')
        ->get();


        // Update this to use the correct view path
        return view('layouts.notification', compact('notifications'));
    }


    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // Check if the notification belongs to the logged-in user
        if ($notification->user_id === Auth::id()) {
            $notification->update(['status' => 'read']);
            return back()->with('success', 'Notification marked as read.');
        }

        return back()->with('error', 'Unauthorized action.');
    }

    /**
     * Delete (mark as deleted) a specific notification.
     */
    public function delete($id)
    {
        $notification = Notification::findOrFail($id);

        // Check if the notification belongs to the logged-in user
        if ($notification->user_id === Auth::id()) {
            $notification->update(['is_deleted' => 1]);
            return response()->json(['message' => 'Notification deleted.'], 200);
        }

        return response()->json(['message' => 'Unauthorized action.'], 403);
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete all notifications for the authenticated user.
     */
    public function deleteAll()
    {
        Notification::where('user_id', Auth::id())
            ->update(['is_deleted' => 1]);

        return response()->json(['message' => 'All notifications deleted.'], 200);
    }
}
