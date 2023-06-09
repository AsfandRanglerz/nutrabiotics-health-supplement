<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $total = 0;
        $total_Commission = 0;
        $orderItems = [];
        return view('pharmacy.report.index',compact('total','total_Commission','orderItems'));
    }

    public function checkReport(Request $request)
{
    $pharmacyId = Auth::guard('pharmacy')->id();
    $orders = Order::where('pharmacy_id', $pharmacyId)->where('status', '1')->get();
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
    return view('pharmacy.report.index', compact('total', 'total_Commission', 'orderItems'));
}


}
