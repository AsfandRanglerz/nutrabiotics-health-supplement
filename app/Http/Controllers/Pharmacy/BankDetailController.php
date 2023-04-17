<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankDetail;
use Illuminate\Support\Facades\Auth;



class BankDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'bankName' => 'required',
            'accountNumber' => 'required',
            'accountHolder' =>'required'
        ]);
        $pharmacy = Auth::guard('pharmacy')->id();
        $data = BankDetail::create([
            'name' => $request->bankName,
            'pharmacy_id' => $pharmacy,
            'accountNumber' => $request->accountNumber,
            'accountHolder' => $request->accountHolder,

        ]);
        return redirect()->route('pharmacy.profile')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bankName' => 'required',
            'accountNumber' => 'required',
            'accountHolder' =>'required'
        ]);

        BankDetail::find($id)->update([
            'name' => $request->bankName,
            'accountHolder' => $request->accountHolder,
            'accountNumber' => $request->accountNumber,
        ]);

        return redirect()->route('pharmacy.profile')->with('success', 'Updated Successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
