<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\enquiries;
use App\Models\masterdancestyle;
use App\Models\masterdancelevel;
use App\Models\mastermemberships;


class EnquiryController extends Controller
{
   public function enquiries(){
    $enquiry_users = enquiries::all();
    $dancestyles = masterdancestyle::pluck('dancestyleName','dancestyleName')->all();
    $dancelevels = masterdancelevel::pluck('dancelevelName','dancelevelName')->all();
    $memberships = mastermemberships::all();
    return view('enquiries.index', compact('enquiry_users','dancestyles','dancelevels','memberships'));
   }
}
