<?php

namespace App\Http\Controllers;

use App\Models\masterdancestyle;
use App\Models\masterdancelevel;
use App\Models\MydanceStyle;
use Illuminate\Http\Request;

class MydancestyleController extends Controller
{
    //
    public function index()
    {
        $dancestyles = masterdancestyle::all();
        // echo $dancestyles; 
        $dancelevels = masterdancelevel::all();
        $selecteddancestyles = MydanceStyle::with('addedby')->get();

        // foreach($selecteddancestyles as $selecteddancestyle){
        //     print_r($selecteddancestyle->danceStyle);
        // }
        // exit;        
        //print_r($selecteddancestyles);
        // echo $dancelevels;
        // exit;
        return view('mydancestyles.index', compact('dancestyles', 'dancelevels','selecteddancestyles'));
    }
    public function store(Request $request)
    {
        $selectedDanceStyles = $request->input('dance_styles');
        $selectedDanceSelections = $request->input('dance_selection');
        // print_r($selectedDanceStyles);
        // print_r($selectedDanceSelections); 
        if ($selectedDanceStyles) {
            foreach ($selectedDanceStyles as $danceStyleId) {
                // Process each selected dance style
                //echo $danceStyleId;
                if (isset($selectedDanceSelections[$danceStyleId])) {
                    foreach ($selectedDanceSelections[$danceStyleId] as $danceLevelId) {
                        $insertmydance = new MydanceStyle;
                        $insertmydance->added_by = Auth()->user()->id;
                        $insertmydance->danceStyle = $danceStyleId;
                        $insertmydance->danceLevel = $danceLevelId;
                        $insertmydance->save();
                        // Perform necessary actions, e.g., save to the database
                        //echo $danceLevelId;
                        
                    }
                }
                else {
                // If no dance levels are selected for a particular dance style, save just the dance style
                $insertmydance = new MydanceStyle;
                $insertmydance->added_by = Auth()->user()->id;
                $insertmydance->danceStyle = $danceStyleId;
                $insertmydance->danceLevel = null; // or handle it as needed
                $insertmydance->save();
            }
            }
        }
        return redirect()->back()->with('status', 'Form submitted successfully!');
        //exit;
        
        // $input = $request->all();
        // $insertmydance = new MydanceStyle;
        // $insertmydance->danceStyle = $input['dance_styles'];
        // $insertmydance->danceLevel = $input['dance_selection'];
    }
}
