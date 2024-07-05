<?php

namespace App\Http\Controllers;

use App\Models\masterdancelevel;
use App\Models\masterdancestyle;
use App\Models\masterdiscounts;
use App\Models\mastermemberships;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MastersController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:View Masters', ['only' => ['danceStyle','danceLevel']]);
         $this->middleware('permission:Create Masters', ['only' => ['dancestylecreate', 'dancelevelcreate']]);
         $this->middleware('permission:Edit Masters', ['only' => ['dancestyleupdate', 'dancelevelupdate']]);
         $this->middleware('permission:Softdelete Masters', ['only' => ['dancestylchangestatus', 'dancelevelchangestatus']]);
    }
    public function danceStyle()
    {
        $dancestyles = masterdancestyle::with('addedby')->with('lastupdatedby')->get();
        return view('masters.dancestyle', compact('dancestyles'));
    }
    public function dancestylecreate(Request $request)
    {
        $request->validate([
            'dancestylename' => 'required'
        ]);
        $input = $request->all();


        $checkdancestyle = masterdancestyle::where('dancestyleName', $input['dancestylename'])->first();

        if ($checkdancestyle == '') {
            $insertdancestyle = new masterdancestyle;
            $insertdancestyle->dancestyleName = $input['dancestylename'];
            $insertdancestyle->dancestyleSlug = Str::slug($input['dancestylename']);
            $insertdancestyle->dancestyleStatus = 1;
            $insertdancestyle->added_by = Auth()->user()->id;
            $insertdancestyle->save();

            if ($insertdancestyle->save()) {
                return redirect()->back()->with('status', 'Dance Style Created Successfully');
            } else {
                return redirect()->back()->with('status', 'Dance style not created');
            }

        } else {
            return redirect()->back()->with('status', 'Dance style already exists');

        }

    }
    public function dancestyleupdate(Request $request)
    {
        $request->validate([
            'dancestylename' => 'required'
        ]);
        $input = $request->all();


        $updatedancestyle = masterdancestyle::where('dancestyleName', $input['dancestylename'])->first();

        if ($updatedancestyle == '') {
            $updatedancestyle = masterdancestyle::where('dancestyleId', $input['dancestyleid'])->first();
            // echo "<pre>";
            // print_r($updatedancestyle); 
            // echo "</pre>";
            // exit;
            $updatedancestyle->dancestyleName = $input['dancestylename'];
            $updatedancestyle->last_updated_by = Auth()->user()->id;
            $updatedancestyle->save();

            if ($updatedancestyle->save()) {
                return redirect()->back()->with('status', 'Dance Style Updated Successfully');
            } else {
                return redirect()->back()->with('status', 'Dance style not Updated');
            }

        } else {
            return redirect()->back()->with('status', 'Dance style already exists');

        }
    }

    public function dancestylchangestatus(Request $request)
    {
        $dancestyleid = $request->input('dancestyleid');
        $status = $request->input('status');

        $getdancestyle = masterdancestyle::find($dancestyleid);
        $getdancestyle->dancestyleStatus = $status;
        // var_dump($getdancestyle); exit;
        $getdancestyle->save();

        if ($getdancestyle->save()) {
            return redirect()->back()->with('status', 'Status changed successfully');
        } else {
            return redirect()->back()->with('status', 'Status not changed');
        }


    }

    public function danceLevel()
    {
        $dancelevels = masterdancelevel::with('addedby')->with('lastupdatedby')->get();
        return view('masters.dancelevel', compact('dancelevels'));
    }
    public function dancelevelcreate(Request $request)
    {
        $request->validate([
            'dancelevelname' => 'required'
        ]);
        $input = $request->all();
        $checkdancelevel = masterdancelevel::where('dancelevelName', $input['dancelevelname'])->first();
        if ($checkdancelevel == '') {
            $insertdancelevel = new masterdancelevel();
            $insertdancelevel->dancelevelName = $input['dancelevelname'];
            $insertdancelevel->dancelevelSlug = Str::slug($input['dancelevelname']);
            $insertdancelevel->dancelevelStatus = 1;
            $insertdancelevel->added_by = Auth()->user()->id;
            $insertdancelevel->save();
            if ($insertdancelevel->save()) {
                return redirect()->back()->with('status', 'Dance level created successfully');
            } else {
                return redirect()->back()->with('status', 'Dance level not created');
            }
        } else {
            return redirect()->back()->with('status', 'Dance level already exists');
        }
    }

    public function dancelevelupdate(Request $request)
    {
        $request->validate([
            'dancelevelname' => 'required'
        ]);
        $input = $request->all();


        $updatedancelevel = masterdancelevel::where('dancelevelname', $input['dancelevelname'])->first();

        if ($updatedancelevel == '') {
            $updatedancelevel = masterdancelevel::where('dancelevelId', $input['dancelevelid'])->first();
            // echo "<pre>";
            // print_r($updatedancelevel); 
            // echo "</pre>";
            // exit;
            $updatedancelevel->dancelevelname = $input['dancelevelname'];
            $updatedancelevel->last_updated_by = Auth()->user()->id;
            $updatedancelevel->save();

            if ($updatedancelevel->save()) {
                return redirect()->back()->with('status', 'Dance Style Updated Successfully');
            } else {
                return redirect()->back()->with('status', 'Dance style not Updated');
            }

        } else {
            return redirect()->back()->with('status', 'Dance style already exists');

        }
    }

    public function dancelevelchangestatus (Request $request)
    {
        $dancelevelid = $request->input('dancelevelid');
        $status = $request->input('status');

        $getdancelevel = masterdancelevel::find($dancelevelid);
        $getdancelevel->dancelevelStatus = $status;
        // var_dump($getdancelevel); exit;
        $getdancelevel->save();

        if ($getdancelevel->save()) {
            return redirect()->back()->with('status', 'Status changed successfully');
        } else {
            return redirect()->back()->with('status', 'Status not changed');
        }
    }

    public function discount(){
        $discounts = masterdiscounts::with('userid')->get();
        return view('masters.discount', compact('discounts'));
    }
    public function discountcreate(Request $request){
        $request->validate([
            'discountname' => 'required',
            'discounttypes' => 'required',
            'min_sessions' => 'required',
            'max_sessions' => 'required',
            'discount_amount' => 'required',
        ]);
        $input = $request->all();
        $checkdiscount = masterdiscounts::where('discountName', $input['discountname'])->first();
        if ($checkdiscount == '') {
            $insertdiscount = new masterdiscounts();
            $insertdiscount->discountName = $input['discountname'];
            $insertdiscount->discountType = $input['discounttypes'];
            $insertdiscount->minSessions = $input['min_sessions'];
            $insertdiscount->maxSessions = $input['max_sessions'];
            $insertdiscount->discountAmount = $input['discount_amount'];
            $insertdiscount->discountStatus = 1;
            $insertdiscount->userId = Auth()->user()->id;
            $insertdiscount->save();
            if ($insertdiscount->save()) {
                return redirect()->back()->with('status', 'Discount created successfully');
            } else {
                return redirect()->back()->with('status', 'Discount not created');
            }
        } else {
            return redirect()->back()->with('status', 'Discount already exists');
        }
    }
    public function discountupdate(Request $request){
        $request->validate([
            'discountId' => 'required|exists:masterdiscounts,discountId',
            'discountname' => 'required|unique:masterdiscounts,discountName,' . $request->discountId . ',discountId',
            'discounttypes' => 'required',
            'min_sessions' => 'required|integer|min:0',
            'max_sessions' => 'required|integer|min:0',
            'discount_amount' => 'required|numeric|min:0',
        ]);

        $input = $request->all();
        $updatediscount = masterdiscounts::where('discountId', $input['discountId'])->first();

        if ($updatediscount) {
            $updatediscount->discountName = $input['discountname'];
            $updatediscount->discountType = $input['discounttypes'];
            $updatediscount->minSessions = $input['min_sessions'];
            $updatediscount->maxSessions = $input['max_sessions'];
            $updatediscount->discountAmount = $input['discount_amount'];
            
            // Check if discount name is unique after update
            $isUnique = masterdiscounts::where('discountName', $input['discountname'])
                                       ->where('discountId', '!=', $input['discountId'])
                                       ->exists();
            
            if ($isUnique) {
                // Redirect back to the modal with an error message
                return redirect()->back()->withInput()->with('status', 'Discount name must be unique');
            }
            
            if ($updatediscount->save()) {
                return redirect()->back()->with('status', 'Discount Updated Successfully');
            } else {
                return redirect()->back()->with('status', 'Discount not Updated');
            }
        } else {
            return redirect()->back()->with('status', 'Discount not found');
        }
    }

    public function discountchangestatus(Request $request){
            $discountid = $request->input('discountid');
            $status = $request->input('status');
            $getdiscount = masterdiscounts::find($discountid);
            $getdiscount->discountStatus = $status;
            // var_dump($getdiscount); exit;
            $getdiscount->save();

            if ($getdiscount->save()) {
                return redirect()->back()->with('status', 'Status changed successfully');
            } else {
                return redirect()->back()->with('status', 'Status not changed');
            }
    }

    public function membership()
    {   
        $memberships = mastermemberships::with('userid')->get();
        return view('masters.membership',compact('memberships'));
    }

    public function membershipcreate(Request $request){
        $request->validate([
            'membershipname' => 'required',
            'membershipbenefits' => 'required',
            'membershipdiscountamount' => 'required|numeric|min:0',
        ]);
        $input = $request->all();
        $checkmembership = mastermemberships::where('membershipName', $input['membershipname'])->first();
        if ($checkmembership == '') {
            $insertmembership = new mastermemberships();
            $insertmembership->membershipName = $input['membershipname'];
            $insertmembership->benefits = $input['membershipbenefits'];
            $insertmembership->membershipDiscountAmount = $input['membershipdiscountamount'];
            $insertmembership->membershipStatus = 1;
            $insertmembership->userId = Auth()->user()->id;
            $insertmembership->save();
            if ($insertmembership->save()) {
                return redirect()->back()->with('status', 'Membership created successfully');
            } else {
                return redirect()->back()->with('status', 'Membership not created');
            }
        } else {
            return redirect()->back()->with('status', 'Membership already exists');
        }
    }

    public function membershipupdate(Request $request){
        $request->validate([
            'membershipId' => 'required|exists:mastermemberships,membershipId',
            'membershipname' => 'required|unique:mastermemberships,membershipName,' . $request->membershipId . ',membershipId',
            'membershipbenefits' => 'required',
            'membershipdiscountamount' => 'required|numeric|min:0',
        ]);

        $input = $request->all();
        $updatemembership = mastermemberships::where('membershipId', $input['membershipId'])->first();

        if ($updatemembership) {
            $updatemembership->membershipName = $input['membershipname'];
            $updatemembership->benefits = $input['membershipbenefits'];
            $updatemembership->membershipDiscountAmount = $input['membershipdiscountamount'];
            
            if ($updatemembership->save()) {
                return redirect()->back()->with('status', 'Discount Updated Successfully');
            } else {
                return redirect()->back()->with('status', 'Discount not Updated');
            }
        } else {
            return redirect()->back()->with('status', 'Discount not found');
        }
    }

    public function membershipchangestatus(Request $request){
        $membershipId = $request->input('membershipid');
        $status = $request->input('status');
        $getmembership = mastermemberships::find($membershipId);
        $getmembership->membershipStatus = $status;
        // var_dump($getmembership); exit;
        $getmembership->save();

        if ($getmembership->save()) {
            return redirect()->back()->with('status', 'Status changed successfully');
        } else {
            return redirect()->back()->with('status', 'Status not changed');
        }        
    }

}
