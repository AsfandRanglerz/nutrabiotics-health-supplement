<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getnotification()
    {
        $notifications = Notification::where([['type', 'admin'], ['read', '0']])->orderBy('created_at', 'desc')->get();
        $unread = Notification::where([['type', 'admin'], ['seen', '0']])->count();
        foreach ($notifications as $notification) {
            $notification->read = '1';
            $notification->save();
        }

        $notification = view('admin.notification.index')->with(['notifications' => $notifications])->render();

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
    public function create()
    {
        return view('admin.notification.create');
    }

    public function store(Request $request)
    {
        if ($request->type == 'pharmacy') {
            $notification = new Notification;
            $notification->type = 'pharmacy';
            $notification->title = $request->title;
            $notification->body = $request->body;
            $notification->file = Auth::guard('admin')->user()->image;
            $notification->url = '#';
            $notification->save();
        } else {
            $body = array(
                'type' => 'user',
                'title' => $request->title,
                'body' => $request->body,
                'file' => Auth::guard('admin')->user()->image,
                'url' => '#',
                // 'type'     => 'accept',
            );

            $user = User::pluck('fcm_token')->toArray();

            $data = [
                'to' => $user,
                'notification' => [
                    'title' => "Request Accepted",
                    'body' => "Request Accepted",
                ],
                'data' => [
                    'RequestData' => $body,
                ],
            ];

            $SERVER_API_KEY = 'AAAANPu72Ro:APA91bFzYN-Qhz9k41f1qGiT3QSJu2mgV4_Nb-8NfO2ck9FEfgLBDtsLemUXrmpVr9nBj3EtNtGJnB2bvmSaD_cVrMZk-8EgMsknaihbc4oIUGpHdwNy9sDwUuC0HDa4UrW_yGpNYDX5';

            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);

        }
        return redirect()->back()->with(['status' => true, 'message' => 'Notification Send Successfully']);
    }

    public function markAllRead()
    {
        $notifications = Notification::where('type', 'admin')->get();
        foreach ($notifications as $notification) {
            $notification->seen = '1';
            $notification->save();
        }
        return response()->json(['success' => true]);
    }

}
