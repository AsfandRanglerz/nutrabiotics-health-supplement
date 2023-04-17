<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PharmacyProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function postOrder(Request $request, $id)
    {
        $AuthUser = auth()->user();
        $AuthId = auth()->id();
        $percentage = Commission::select('percentage')->first();
        $randomCode = random_int(10000000, 99999999);
        $order = new Order;
        $order->pharmacy_id = $request->id;
        $order->user_id = $AuthId;
        $order->percentage = $percentage->percentage;
        $order->code = $randomCode;

        $product = Product::where('id', $id)->first();
        $orderItem = new OrderItem;
        $orderItem->product_id = $product->id;
        $orderItem->quantity = $request->quantity;
        $orderItem->price = $product->price;
        $orderItem->d_per = $product->d_per;
        $orderItem->sub_total = $orderItem->price * $orderItem->quantity;
        $orderItem->total = ($orderItem->price - ($orderItem->d_per * $orderItem->price / 100)) * $orderItem->quantity;
        $orderItem->commission = $orderItem->total * $order->percentage / 100;

        $pharmacyProduct = PharmacyProduct::where('pharmacy_id', $order->pharmacy_id)
            ->where('product_id', $orderItem->product_id)
            ->first();

        if ($pharmacyProduct) {
            $stock = $pharmacyProduct->stock;
            if ($stock >= $orderItem->quantity) {
                $order->save();
                $orderItem->order_id = $order->id;
                $orderItem->save();
                $pharmacyProduct->decrement('stock', $orderItem->quantity);
                $stock = $pharmacyProduct->stock;

                $notification = new Notification;
                $notification->user_id = $AuthId;
                $notification->pharmacy_id = $order['pharmacy_id'];
                $notification->type = 'pharmacy';
                $notification->title = 'Order';
                $notification->body = $product['product_name'] . ' - ' . $orderItem['quantity'] . ' - ' . $product['price'];
                $notification->file = $AuthUser->image;
                $notification->url = 'pharmacy/order';
                $notification->save();

                $data = array('order' => $order, 'item' => $orderItem, 'stock' => $stock);
                return $this->sendSuccess('Order Confirmed', $data);
            } else {
                return $this->sendError('Insufficient stock');
            }
        } else {
            $data = array('order' => $order, 'item' => $orderItem, 'stock' => $stock);
            return $this->sendSuccess('Order Confirmed', $data);
        }
    }

    public function getCoupon()
    {
        $authId = Auth::id();
        $data = Order::where('user_id', $authId)->get();
        $coupon = OrderItem::with('product')->with('order.pharmacy')->whereIn('order_id', $data->pluck('id'))->orderBy('id', 'DESC')->get();
        return $this->sendSuccess('Coupon data', $coupon);
    }

    public function OrderAccept(Request $request)
    {
        $authId = Auth::id();
        $order = Order::where('user_id', $authId)->where('id', $request->order_id)->first();
        $order->update(['status' => $order->status == 0 ? '1' : '0']);

        if ($order->status == 1) {
            $totalCommission = $order->orderItems->sum('commission');
            $pharmacy = $order->pharmacy;
            $pharmacy->balance += $totalCommission;
            $pharmacy->save();
        }

        $orderItem = $order->orderItems->first();
        $notification = new Notification();
        $notification->user_id = $order->user_id;
        $notification->pharmacy_id = $order->pharmacy_id;
        $notification->title = "Order Accepted";
        $notification->body = $orderItem->product->product_name . '-' . $orderItem->product->price;
        $notification->save();

        return response()->json(['message' => 'Order accepted successfully']);
    }

}
