<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserLoginPassword;
use App\Models\Admin;
use App\Models\BankDetail;
use App\Models\City;
use App\Models\Country;
use App\Models\Pharmacy;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Response;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Pharmacy::orderBy('id', 'DESC')->get();

        // $accounts = [];
        // foreach ($data as $pharmacy) {
        //     $account = BankDetail::where('pharmacy_id', $pharmacy->id)->get();
        //     $accounts[$pharmacy->id] = $account;
        // }
        // return $accounts;
        // Uncomment this line for testing
        return view('admin.pharmacy.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $path = public_path('countries.json');
       $json = file_get_contents($path);
       $country = json_decode($json);
        return view('admin.pharmacy.create', compact('country'));
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
            // 'name' => 'required',
            'email' => 'required|unique:pharmacies,email|email',
            'country' => 'required',
            'city' => 'required',
            // 'state' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/users/1677572377.jpg';
        }

        /**generate random password */
        $password = random_int(10000000, 99999999);
        $company = Pharmacy::create([
            'name' => $request->pharmacy_name,
            'phone' => $request->phone,
            'country' => $request->country,
            // 'state' => $request->state,
            'city' => $request->city,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($password),
        ] + ['image' => $image]);
        $company = Admin::find(Auth::guard('admin')->id());

        /** assign the role  */
        $message['admin'] = $company->name;
        $message['name'] = $request->pharmacy_name;
        $message['email'] = $request->email;
        $message['password'] = $password;

        try {
            Mail::to($request->email)->send(new UserLoginPassword($message));
            return redirect()->route('pharmacy.index')->with(['status' => true, 'message' => 'Created Successfully']);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()
                ->with(['status' => false, 'message' => $th->getMessage()]);
        }
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
    public function edit($id)
    {
        $data = Pharmacy::find($id);
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
        return view('admin.pharmacy.edit', compact('data', 'data1'));
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
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $company = Pharmacy::findOrFail($id);

        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/img/users/' . $company->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/img/users', $filename);
            $image = 'public/admin/assets/img/users/' . $filename;
        } else {
            $image = $company->image;
        }

        $company->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'country' => $request->country,
            // 'state' => $request->state,
            'city' => $request->city,
            'address' => $request->address,
            'image' => $image,
        ]);

        return redirect()->route('pharmacy.index')->with(['status' => true, 'message' => 'Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pharmacy::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Deleted Successfully']);
    }

    public function status($id)
    {
        $data = Pharmacy::find($id);
        $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }


//     public function getState(Request $request)
//     {
//         $path = public_path('states.json');
//         $json = file_get_contents($path);
//         $data = json_decode($json);
//          $data['states'] = collect($data)->where("country_name",$request->country_id)
//         ->pluck('name');
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



    //Account Detail
    public function accountDetail(Request $request)
    {
        $detail = BankDetail::where('pharmacy_id', $request->id)->first();
        // dd($detail);
        $data = view('admin/pharmacy/account_detail/index')->with([
            'detail' => $detail,
        ])->render();
        return response()->json([
            'success' => 'Status  successfully',
            'data' => $data,
        ]);
    }
}
