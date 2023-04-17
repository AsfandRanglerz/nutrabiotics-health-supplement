<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getnotification()
    {
        $AuthId = Auth::guard('pharmacy')->id();
        $notifications = Notification::where([['type', 'pharmacy'], ['read', '0']])
            ->where(function ($query) use ($AuthId) {
                $query->where('pharmacy_id', $AuthId)
                    ->orWhereNull('pharmacy_id');
            })
            ->orderByDesc('seen')
            ->orderByDesc('id')
            ->get();
        $unread = Notification::where('type', 'pharmacy')
            ->where(function ($query) use ($AuthId) {
                $query->where('pharmacy_id', $AuthId)
                    ->orWhereNull('pharmacy_id');
            })
            ->where('seen', '0')
            ->count();

        foreach ($notifications as $notification) {
            $notification->read = '1';
            $notification->save();
        }

        $notification = view('pharmacy.notification.index')->with(['notifications' => $notifications])->render();

        return response()->json([
            'success' => 'Status updated successfully',
            'unread' => $unread,
            'notification' => $notification,
        ]);
    }

    public function urlNotification(Request $request)
    {
        $notification = Notification::where('id', $request->id)->first();
        $notification->seen = '1';
        $notification->save();
    }
    public function markAllRead(Request $request)
    {
        $notifications = Notification::where('pharmacy_id', $request->id)->get();
        // return $notifications;
        foreach ($notifications as $notification) {
            $notification->seen = '1';
            $notification->save();
        }
        return response()->json(['success' => true]);
    }


}
