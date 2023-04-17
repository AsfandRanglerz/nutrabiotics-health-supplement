<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\Commission;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PharmacyController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('pharmacy')->id();
        $data['pharmacyproduct'] = PharmacyProduct::wherePharmacy_id($authId)->count();
        $data['orderpending'] = Order::wherePharmacy_id($authId)->where('status', '0')->count();
        $data['orderapproved'] = Order::wherePharmacy_id($authId)->where('status', '1')->count();
        $data['commission'] = Commission::select('percentage')->first();

        // $company = PharmacyProduct::with('product.category')->where('pharmacy_id',$authId);
        $data['pharmacy'] = Pharmacy::find($authId);
        //  $company = PharmacyProduct::find($authId);
        // $data['company'] = $company;
        return view('pharmacy.index', $data);
    }
    public function getProfile()
    {
        $auth = Auth::guard('pharmacy')->id();
        $data = Pharmacy::find($auth);
        $path = public_path('countries.json');
        $json = file_get_contents($path);
        $data1['country'] = json_decode($json);
        // $data1['country'] = Country::get();
        // $path = public_path('states.json');
        // $json = file_get_contents($path);
        // $Statedata = json_decode($json);
        // $data1['state'] = collect($Statedata)->where('country_name', $data->country)->all();
        // $path = public_path('cities.json');
        // $json = file_get_contents($path);
        // $Citydata = json_decode($json);
        // $data1['city'] = collect($Citydata)->where('state_name', $data->state)->all();
        $account = BankDetail::with('pharmacy')->where('pharmacy_id', $auth)->first();
        return view('pharmacy.auth.profile', compact('data', 'account', 'data1'));
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/img/users/'), $filename);
            $image = 'public/admin/assets/img/users/' . $filename;
        } else {
            $image = Auth::guard('pharmacy')->user()->image;
        }

        Pharmacy::find($id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            // 'state' => $request->state,
            'city' => $request->city,
            'image' => $image,
        ]);

        return redirect()->route('pharmacy.profile')->with('success', 'Updated Successfully');
    }

    public function logout()
    {
        Auth::guard('pharmacy')->logout();
        return redirect()->route('login')->with('success', "You've Logout Successfully");
    }

    //Change Password
    public function profile_change_password(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required',
        ]);
        $auth = Auth::guard('pharmacy')->user();
        if (!Hash::check($request->current_password, $auth->password)) {
            return back()->with('error', "Current Password is Invalid");
        } else if (strcmp($request->current_password, $request->new_password) == 0) {
            return redirect()->back()->with('error', "New Password cannot be same as your current password.");
        } else {
            $user = Pharmacy::find($auth->id);
            $user->password = Hash::make($request->new_password);
            $user->save();
            return back()->with('success', 'Updated Successfully');
        }
    }

//     public function getState(Request $request)
//     {
//         $path = public_path('states.json');
//         $json = file_get_contents($path);
//         $data = json_decode($json);
//          $data['states'] = collect($data)->where("country_name",$request->country_id)->pluck('name');
//          return response()->json($data);
//     }
//     public function getCity(Request $request)
// {
//     $path = public_path('cities.json');
//     $json = file_get_contents($path);
//     $data = json_decode($json);
//     $data['cities'] = collect($data)->where('state_name', $request->state_id)->pluck('name');
//     return response()->json($data);
// }

}
