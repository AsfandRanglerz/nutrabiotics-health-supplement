<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pharmacy;
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


    $orders = Order::where('pharmacy_id',$request->pharmacy_id)->where('status', '1')->get();
    $orderItems = OrderItem::whereIn('order_id', $orders->pluck('id'))->orderBy('id', 'DESC')->get();
    $total = 0;
    $total_Commission = 0;
    $filteredOrders = [];

    foreach ($orderItems as $order) {
        $createdAt = $order->created_at;
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $createdAt);
        $monthYear = $datetime->format('Y-m-d');
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if ($start_date <= $monthYear && $monthYear <= $end_date) {
            $total += $order->total;
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
