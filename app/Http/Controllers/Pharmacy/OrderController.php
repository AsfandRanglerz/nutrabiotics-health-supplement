<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pharmacy;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('pharmacy')->id();
        $data = Order::where('pharmacy_id', $authId)->get();
        $orderItems = OrderItem::with('product.subcategory.category')->with('order.user')->whereIn('order_id', $data->pluck('id'))->orderBy('id', 'DESC')->get();
        return view('pharmacy.order.index', compact('orderItems'));
    }
    public function status($id)
    {
        $order = Order::with('pharmacy', 'orderItems.product')->find($id);
        $order->update(['status' => $order->status == 0 ? '1' : '0']);
        $orderItems = $order->orderItems;

        $body = array(
            'id' => $id,
            'code' => $order->code,
            'pharmacy_name' => $order->pharmacy->name,
        );
        foreach ($orderItems as $orderItem) {
            $body['product_id'] = $orderItem->product->id;
            $body['product_name'] = $orderItem->product->product_name;
        }

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
        // dd($response);
        if ($order->status == '1')
        {
            $orderItems = OrderItem::where('order_id', $order->id)->first();
            $balance = ($orderItems->commission + $order->pharmacy->balance);
            $pharmacyId = $order->pharmacy->id;
            $pharmacy = Pharmacy::find($pharmacyId);
            $pharmacy->balance = $balance;
            $pharmacy->save();


            // $orderItems->commission
        }
        $notification = new Notification();
        $notification->user_id = $order->user_id;
        $notification->pharmacy_id = $order->pharmacy_id;
        $notification->title = "Order Accepted";
        $notification->body = $orderItem->product->product_name . '-' . $orderItem->product->price . '-' . $body['code'];
        $notification->save();

        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }
    public function orderDetail(Request $request)
    {
        $order = Order::with('pharmacy', 'user', 'orderItems.product.subcategory.category')->find($request->id);
        $orderItems = $order->orderItems;
        $data = view('pharmacy/order/modal', compact('order', 'orderItems'))->render();
        return response()->json([
            'success' => 'Status successfully',
            'data' => $data,
        ]);
    }
    public function notification()
    {
        $pharmacy = Auth::guard('pharmacy')->id();
        $notifications = Notification::where('type', 'pharmacy')->where('pharmacy_id',$pharmacy)->latest()->get();
        return response()->json(['notifications' => $notifications]);
    }

}
