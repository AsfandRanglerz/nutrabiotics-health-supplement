<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pharmacy;
use App\Models\HowOrder;
use App\Models\Notification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with('product.subcategory.category')->with('order.user')->with('order.pharmacy')->orderBy('id', 'DESC')->get();
        $order = Order::query()->update(['seen' => '1']);
        return view('admin.order.index', compact('orderItems'));
    }

    public function orderDetail(Request $request)
    {
        $order = Order::with('pharmacy', 'user', 'orderItems.product.subcategory.category')->find($request->id);
        $orderItems = $order->orderItems;
        $data = view('admin/order/modal', compact('order', 'orderItems'))->render();
        return response()->json([
            'success' => 'Status successfully',
            'data' => $data,
        ]);
    }
    public function status($id){
        $admin = Auth::guard('admin')->user();
        $orders = Order::find($id);
        $orders->status = '2';
        $orderItem = OrderItem::where('order_id',$id)->first();
        $pharmacy = Pharmacy::where('id',$orders->pharmacy_id)->first();
        $pharmacy->balance = $pharmacy->balance - $orderItem->commission;
        $orders->save();
        $pharmacy->save();
        $notification = new Notification();
        $notification->user_id = $orders->user_id;
        $notification->pharmacy_id = $orders->pharmacy_id;
        $notification->type = "pharmacy";
        $notification->title = "Order Request respond from admin";
        $notification->body = "$orders->code - Inactive the order";
        $notification->file = $admin->image;
        $notification->url = 'pharmacy/order';
        $notification->save();
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }

    public function OrderPageIndex(){
           $data = HowOrder::first();
           return view('admin.order.orderPage.index', compact('data'));
    }
    public function OrderPageEdit($id){
        $data = HowOrder::find($id);
        return view('admin.order.orderPage.edit', compact('data'));
    }
    public function OrderPageUpdate(Request $request,$id){
        HowOrder::find($id)->update([
            'description' => $request->description,
        ]);

        return redirect()->route('orderPage.index')->with(['status' => true, 'message' => 'Update Successfully']);
    }



}
