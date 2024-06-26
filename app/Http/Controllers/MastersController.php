<?php

namespace App\Http\Controllers;

use App\Models\masterdancelevel;
use App\Models\masterdancestyle;
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
}
