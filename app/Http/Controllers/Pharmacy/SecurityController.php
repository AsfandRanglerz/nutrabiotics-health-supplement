<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\About;
use App\Models\TermCondition;
use App\Models\PrivacyPolicy;
class SecurityController extends Controller
{
    public function faq()
    {
        $data = Faq::orderby('id', 'DESC')->get();
        return view('pharmacy.security.faq', compact('data'));
    }
    public function aboutUs()
    {
         $data = About::first();
        return view('pharmacy.security.aboutUs',compact('data'));
    }
    public function termCondition()
    {
        $data = TermCondition::first();
        return view('pharmacy.security.termCondition', compact('data'));
    }
    public function privacyPolicy()
    {
        $data = PrivacyPolicy::first();
        return view('pharmacy.security.privacyPolicy', compact('data'));
    }
}
