<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CountryViseController extends Controller
{
    // public function index()
    // {
    //     $data['countries'] = Country::get(["name","id"]);
    //     return view('admin.countryVise.create', compact('data'));
    // }
    // public function getState(Request $request)
    // {
    //     $data['states'] = State::where("country_id",$request->country_id)
    //                 ->get(["name","id"]);
    //     return response()->json($data);
    // }
    // public function getCity(Request $request)
    // {
    //     $data['cities'] = City::where("state_id",$request->state_id)
    //                 ->get(["name","id"]);
    //     return response()->json($data);
    // }
}
