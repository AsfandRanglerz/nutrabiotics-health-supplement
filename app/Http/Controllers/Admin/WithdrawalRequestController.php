<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\Notification;
use App\Models\WithDrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WithdrawalRequestController extends Controller
{
    public function index()
    {
        $data = WithDrawalRequest::with('pharmacy.bankDetail')->latest()->get();
        //    dd($data);
        return view('admin.withdrawalRequest.index', compact('data'));
    }
    public function status($id)
    {
        $data = WithDrawalRequest::find($id);
        $data->update(['status' => $data->status == 0 ? '1' : '0']);
        if($data->status == '1')
        {
            $notification = new Notification;
            $notification->pharmacy_id = $data['pharmacy_id'];
            $notification->type = 'pharmacy';
            $notification->title = 'Request Approved';
            $notification->body = $data['payment'];
            $notification->file = Auth::guard('admin')->user()->image;
            $notification->url = 'pharmacy/withDrawal';
            $notification->save();
        }
        return redirect()->back()->with(['status' => true, 'message' => 'Status Updated Successfully']);
    }
    //Account Detail
    public function accountDetail(Request $request)
    {
        $detail = BankDetail::where('pharmacy_id', $request->id)->first();
        $data = view('admin/withdrawalRequest/modal')->with([
            'detail' => $detail,
        ])->render();
        return response()->json([
            'success' => 'Status  successfully',
            'data' => $data,
        ]);
    }
    public function notification()
    {
        $notifications = Notification::where('type', 'admin')->latest()->get();
        return response()->json(['notifications' => $notifications]);
    }

}
