<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('layouts.notification', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return back()->with('success', 'Notification marked as read.');
        }

        return back()->with('error', 'Notification not found or unauthorized.');
    }

    /**
     * Delete a specific notification.
     */
    public function delete($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted.'], 200);
        }

        return response()->json(['message' => 'Notification not found or unauthorized.'], 403);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete all notifications for the authenticated user.
     */
    public function deleteAll()
    {
        Auth::user()->notifications()->delete();

        return response()->json(['message' => 'All notifications deleted.'], 200);
    }

    public function sendTestEmail()
    {
        Mail::to('abrigoslourdsam@gmail.com')->send(new TestEmail());
        return 'Test email sent!';
    }

}
