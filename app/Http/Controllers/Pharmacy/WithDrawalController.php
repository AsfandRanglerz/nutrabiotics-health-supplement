<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\OrderItem;
use App\Models\BankDetail;
use App\Models\Notification;
use App\Models\WithDrawalRequest;
use Illuminate\Support\Facades\Auth;



class WithDrawalController extends Controller
{
    // public function index()
    // {
    //     $userId = 24; // Replace with the ID of the user you want to retrieve orders for
    //     $orders = Order::with('orderItems.product')->where('user_id', $userId)->get();

    //     $firstPayment = Payment::with('orderItem.order.user', 'orderItem.pharmacy', 'orderItem.product')
    //         ->whereHas('orderItem.order', function ($query) use ($userId) {
    //             $query->where('user_id', $userId);
    //         })
    //         ->first();

    //     $amount = 0;
    //     // return $firstPayment->orderItem->product->price;
    //     $amount += $firstPayment->orderItem->quantity * $firstPayment->orderItem->product->price;

    //     // Filter the orders by the status of the first payment
    //     $filteredOrders = $orders->filter(function ($order) use ($firstPayment) {
    //         return $firstPayment && $order->status == $firstPayment->orderItem->order->status;
    //     });
    //     // dd($filteredOrders);
    //     // Pass the filtered orders and the payment data to the view
    //     return view('pharmacy.withDrawal.index', compact('firstPayment', 'filteredOrders', 'amount'));
    // }

    public function index() {
        $pharmacyId = Auth::guard('pharmacy')->id();
    //     $orders = Order::with('orderItems')->where('pharmacy_id', $pharmacyId)->pluck('id')->toArray();
    //    $total= OrderItem::whereIn('order_id',$orders)->sum('commission');
    //     $data = Pharmacy::find($pharmacyId);
    //     $data->balance = $total;
    //     $data->save();

        $payment = WithDrawalRequest::where('pharmacy_id', $pharmacyId)->where('status','0')->sum('payment');
        // return $payment;
        $withdrawn = WithDrawalRequest::where('pharmacy_id', $pharmacyId)->where('status','1')->sum('payment');
        // return $withdrawn;
        return view('pharmacy.withDrawal.index',compact('payment','withdrawn'));
    }

    public function store(Request $request)
{
    $pharmacy = Auth::guard('pharmacy')->user();
    $account = $pharmacy->bankDetail;

    if (!$account) {
        return redirect()->route('pharmacy.profile')->with('error', 'Please add your finance details first.');
    }

    $withdrawal = new WithdrawalRequest();
    $withdrawal->pharmacy_id = $pharmacy->id;
    $withdrawal->payment = $request->payment;
    $withdrawal->save();

    $pharmacy->balance -= $request->payment;
    $pharmacy->save();

    $data = new Notification();
    $data->pharmacy_id = $pharmacy->id;
    $data->title = 'Withdrawal Request';
    $data->type = 'admin';
    $data->body = $pharmacy->name . ' - ' . $withdrawal->payment;
    $data->file = $pharmacy->image;
    $data->url = 'admin/withdrawal-request-index';
    $data->save();

    return redirect()->route('pharmacy.withDrawal.index')->with('success' ,'Withdrawal request sent successfully.');
}




}
