<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
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
    

}
