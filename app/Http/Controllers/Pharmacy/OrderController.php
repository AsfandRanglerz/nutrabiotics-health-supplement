<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Mail\OrderRequestForm;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pharmacy;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('pharmacy')->id();
        $data = Order::where('pharmacy_id', $authId)->get();
        $order = Order::where('pharmacy_id',$authId)->update(['seen' => '1']);
        $orderItems = OrderItem::with('product.subcategory.category')->with('order.user')->whereIn('order_id', $data->pluck('id'))->orderBy('id', 'DESC')->get();
        return view('pharmacy.order.index', compact('orderItems'));
    }
    public function status($id)
    {
        $order = Order::with('pharmacy', 'orderItems.product')->find($id);
        $orderItems = $order->orderItems;
        $products = [];

        foreach ($orderItems as $orderItem) {
            $products[] = [
                'product_id' => $orderItem->product->id,
                'product_name' => $orderItem->product->product_name,
            ];
        }
        $selectedData = [
            'order_id' => $id,
            'user_id' => $order->user_id,
            'pharmacy_id' => $order->pharmacy_id,
            'code' => $order->code,
            'pharmacy_name' => $order->pharmacy->name,
            'products' => $products,
            'title' =>'Order Approval Request'
        ];
        $notification = new Notification();
        $notification->user_id = $order->user_id;
        $notification->pharmacy_id = $order->pharmacy_id;
        $notification->title = "Order Approval Request";
        $notification->body = json_encode($selectedData);
        $notification->save();

        $body = [
            'notification_id' => $notification->id,
            'order_id' => $id,
            'user_id' => $order->user_id,
            'pharmacy_id' => $order->pharmacy_id,
            'code' => $order->code,
            'pharmacy_name' => $order->pharmacy->name,
            'products' => $products,
            'title' =>'Order Approval Request',
            'created_at'=> $notification->created_at
                ];
        //  return $body;

        $user = User::where('id', $order->user_id)->first();
        $data = [
            'to' => $user->fcm_token,
            'notification' => [
                'title' => 'Order Approval Request',
                'body' => 'Request Accepted',
            ],
            'data' => [
                'RequestData' => $body,
            ],
        ];

        $SERVER_API_KEY = 'AAAANPu72Ro:APA91bFzYN-Qhz9k41f1qGiT3QSJu2mgV4_Nb-8NfO2ck9FEfgLBDtsLemUXrmpVr9nBj3EtNtGJnB2bvmSaD_cVrMZk-8EgMsknaihbc4oIUGpHdwNy9sDwUuC0HDa4UrW_yGpNYDX5';

        $dataString = json_encode($data);
        // dd($dataString);
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
        curl_close($ch);
        // return $response;
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
        $notifications = Notification::where('type', 'pharmacy')->where('pharmacy_id', $pharmacy)->latest()->get();
        return response()->json(['notifications' => $notifications]);
    }
    public function getRequestForm(Request $request){
        // return $request->id;
        $data = Order::find($request->id);
        // return $data;
        return response()->json([
            'success' => 'Status successfully',
            'data' => [
                'order_id' => $data->id,
            ],
        ]);
    }

    public function requestForm(Request $request){
        $pharmacy = Auth::guard('pharmacy')->user();
        $data = Order::find($request->order_id);
        $data->description = $request->description;
        $data->save();
        $notification = new Notification();
        $notification->user_id = $data->user_id;
        $notification->pharmacy_id = $data->pharmacy_id;
        $notification->type = "admin";
        $notification->title = "Order Request";
        $notification->body = $request->description;
        $notification->file = $pharmacy->image;
        $notification->url = 'admin/order';

        $notification->save();

        //mail
        $admin = Admin::find(Auth::guard('admin')->id());


        /** assign the role  */
        $message['admin'] = $admin->name;
        $message['pharmacy_name'] = $pharmacy->name;
        $message['description'] = $data->description;
        $message['code'] = $data->code;

        try {
            Mail::to($admin->email)->send(new OrderRequestForm($message));
            return redirect('pharmacy/order')->with('success', 'Request Send Successfully');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()
                ->with(['status' => false, 'message' => $th->getMessage()]);
        }

    }

}
