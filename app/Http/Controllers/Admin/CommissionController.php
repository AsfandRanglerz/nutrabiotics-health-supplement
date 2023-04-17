<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commission;


class CommissionController extends Controller
{
    public function index(){
         $data = Commission::all();
         return view('admin.commission.index', compact('data'));
     }

    public function edit($id){
        $data = Commission::find($id);
        return view('admin.commission.edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'percentage' => 'required',
        ]);

        $data = Commission::find($id);
        $data->percentage = $request->input('percentage');
        $data->update();
        return redirect()->route('commission.index')->with(['status' => true, 'message' => 'Updated Successfully']);


    }
}
