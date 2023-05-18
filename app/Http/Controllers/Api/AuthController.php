<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordUser;
use App\Models\User;
use App\Models\Country;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 404);
        }
        $data = $request->only(['name', 'email', 'phone', 'password','country_id']);
        $data['image'] = 'public/admin/assets/images/users/1677742785.jpg';
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        $user['token'] = $user->createToken('token')->plainTextToken;
        $user['fcm_token'] = $request->fcm_token;
        return $this->sendSuccess('Register Successfully', $user);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->sendError('Invalid email or password');
        }
        $user = auth()->user();
        if ($user->is_active == 1) {
            return $this->sendError("Your account has been blocked");
        }
        $user->update(['fcm_token' => $request->fcm_token]);
        $user->refresh();
        $user->makeHidden(['password']);
        $user['token'] = $user->createToken('authToken')->plainTextToken;
        return $this->sendSuccess('Login Successfully', $user);
    }

    public function logout(Request $request)
    {
        $data = User::find(Auth::id());
        $data->fcm_token = null;
        $data->save();
        DB::table('personal_access_tokens')->where(['tokenable_id' => Auth::id()])->delete();
        return $this->sendSuccess('User Logout Successfully');
    }
    public function forgetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $user = User::where('email', $request->email)->first();
        if (isset($user)) {
            $email = DB::table('password_resets')->where('email', $request->email)->delete();
            $email = DB::table('password_resets')->where('email', $request->email)->first();
            if ($email) {
                return back()->with('message', 'Otp  has been already sent');
            } else {
                $token = random_int(100000, 999999);
                $token = Str::random(30);
                $otp = random_int(1000, 9999);
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'otp' => $otp,
                    'created_at' => Carbon::now(),
                ]);
                $data['otp'] = $otp;
                Mail::to($request->email)->send(new ResetPasswordUser($data));
                return $this->sendSuccess('Otp has been sent to email', ['email' => $request->email]);
            }
        }
        return $this->sendError('Email does not exist');
    }
    // Confirm Token
    public function confirmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $token_data = DB::table('password_resets')->where('otp', $request->otp)->where('email', $request->email)->first();
        if (isset($token_data)) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return $this->sendSuccess('Otp Confirmed Successfully', ['email' => $token_data->email]);
        } else {
            return $this->sendError('Otp Invalid');
        }
    }
    // Submit Reset Password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError('Email not found');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return $this->sendSuccess('Password reset successfully');
    }
// Edit Profile
    public function editProfile()
    {
        // dd('ali');
        $user = User::find(auth()->id());
        return $this->sendSuccess('User data sent  successfully', compact('user'));
    }
// update Profile
    public function updateProfile(Request $request)
    {
        $data = User::find(Auth::id());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'required|unique:users,email|email',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $userData = $request->only(['name']);
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('images'), $filename);
            $userData['image'] = 'public/images/' . $filename;
        }
        $user = User::find(Auth::id())->update($userData);
        $data = User::find(Auth::id());
        return $this->sendSuccess('Updated Successfully', compact('data'));

    }
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Current Password is Invalid');
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->sendSuccess('Password Updated Successfully.');
    }

    public function dealNotification()
    {
      $notification = Notification::where('type','user')->where('title','Deal')->orderBy('created_at','DESC')->get();
      return $this->sendSuccess('Deal Notification', compact('notification'));
    }

    public function allNotification(){
        $notification = Notification::where('type','user')->latest()->get();
      return $this->sendSuccess('All Notification', compact('notification'));
    }

    public function seenNotification($id){
        $notification = Notification::where('id',$id)->first();
        $notification->seen = '1';
        $notification->save();
        return $this->sendSuccess('Seen  Notification');

    }

    public function getcountry(){
           $data = Country::all();
           return $this->sendSuccess('Countries', $data);

    }
}
