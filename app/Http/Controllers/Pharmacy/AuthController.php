<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Mail\CompanyRegister;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Pharmacy;



class AuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);
    $credentials = ['email' => $request->email, 'password' => $request->password];
    if (Auth::guard('pharmacy')->attempt($credentials)) {
        $pharmacy = Auth::guard('pharmacy')->user();
        if ($pharmacy->is_active == 1) {
            return redirect()->back()->with('error', "Your account has been blocked");
        }

        return redirect()->route('pharmacy.dashboard')->with('success', "You've logged in successfully.");
    }

    return redirect()->back()->with('error', "Your email or password is invalid.");
}
    public function register(Request $request)
    {
        $request->validate([
            'pharmacy_name' => 'required',
            'email' => 'required|unique:pharmacies,email|email',
            'phone' => 'required',
            'password' => 'required',
            'confirm_password' => 'same:password',
            // 'designation' => 'required',
        ]);
        $image = 'public/admin/assets/images/users/1677572377.jpg';
        /**generate random password */
        $company = Pharmacy::create([
            'name' => $request->pharmacy_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_active'=> '1'
        ] + ['image' => $image]);

        /** assign the role  */
        $message['name'] = $request->pharmacy_name;
        $message['email'] = $request->email;

        try {
            Mail::to($request->email)->send(new CompanyRegister($message));
            return redirect()->route('View_login')->with('success' , "Registered Successfully");
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()
                ->with(['status' => false, 'message' => $th->getMessage()]);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'=>'required|exists:pharmacies,email',
        ]);
        $exists = DB::table('password_resets')->where('email',$request->email)->first();
        if ($exists){
            return back()->with('success','Reset Password link has been already sent');
        }else{
            $token = Str::random(30);
            DB::table('password_resets')->insert([
                'email'=>$request->email,
                'token'=>$token,
            ]);

            $data['url'] = url('change_password',$token);
            Mail::to($request->email)->send(new ResetPasswordMail($data));
            return back()->with('success','Reset Password Link Send Successfully');
        }
    }
    public function change_password($id)
    {

        $user = DB::table('password_resets')->where('token',$id)->first();

        if(isset($user))
        {
            return view('pharmacy.auth.chnagePassword',compact('user'));
        }
    }
    public function changePassword (Request $request)
    {

       $request->validate([
            'password' => 'required|min:8',
            'confirmed' => 'required',

        ]);
       if ($request->password !=$request->confirmed)
       {

           return back()->with('error' , 'Password not matched');
       }
        $password=bcrypt($request->password);
        $tags_data = [
            'password' => bcrypt($request->password)
        ];
        if (Pharmacy::where('email',$request->email)->update($tags_data)){
            DB::table('password_resets')->where('email',$request->email)->delete();
            return redirect()->route('View_login')->with('success', 'Password Reset Successfully');
        }


    }

}
