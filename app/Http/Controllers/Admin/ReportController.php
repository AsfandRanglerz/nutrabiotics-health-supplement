<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $total = 0;
        $total_Commission = 0;
        $orderItems = [];
        return view('admin.report.index',compact('total','total_Commission','orderItems'));
    }

    public function checkReport(Request $request)
{
    $orders = Order::where('status', '1')->get();
    $orderItems = OrderItem::whereIn('order_id', $orders->pluck('id'))->orderBy('id', 'DESC')->get();
    $total = 0;
    $total_Commission = 0;
    $filteredOrders = [];

    foreach ($orderItems as $order) {
        $createdAt = $order->created_at;
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $createdAt);
        $monthYear = $datetime->format('m-Y');
        $datepickerDate = $request->selected_date;
        if ($monthYear == $datepickerDate) {
            $total += $order->price;
            $total_Commission += $order->commission;
            $filteredOrders[] = $order->order;
        }
    }
    // if (empty($filteredOrders)) {
    //     $message = "No orders found for the selected month.";
    //     return view('pharmacy.report.index', compact('message'));
    // }
    $orderItems = OrderItem::with('product.subcategory.category')->with('order.user')->whereIn('order_id', collect($filteredOrders)->flatten()->pluck('id'))->orderBy('id', 'DESC')->get();
    return view('admin.report.index', compact('total', 'total_Commission', 'orderItems'));
}

}
