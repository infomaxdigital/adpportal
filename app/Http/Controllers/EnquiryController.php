<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\enquiries;

class EnquiryController extends Controller
{
   public function enquiries(){
    $enquiry_users = enquiries::all();
    return view('enquiries.index', compact('enquiry_users'));
   }
}
