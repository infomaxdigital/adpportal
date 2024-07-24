<?php

namespace App\Http\Controllers;

use App\Models\masterdancestyle;
use App\Models\masterdancelevel;
use App\Models\MydanceStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MydancestyleController extends Controller
{
    public function index()
    {
        $dancestyles = masterdancestyle::all();
        $dancelevels = masterdancelevel::all();

        $mydancestyles = MydanceStyle::with('teacherId')
            ->where('teacherId', Auth::id())
            ->get()
            ->keyBy('danceStyle')
            ->toArray();
        //     echo "<pre>";
        // print_r($mydancestyles); echo "</pre>";exit;
        foreach ($mydancestyles as &$mydancestyle) {
            $mydancestyle['danceLevel'] = json_decode($mydancestyle['danceLevel'], true);
        }

        return view('mydancestyles.index', compact('dancestyles', 'dancelevels', 'mydancestyles'));
    }

    public function store(Request $request)
    {
        $selectedDanceStyles = $request->input('dance_styles', []);
        $selectedDanceSelections = $request->input('dance_selection', []);
        $teacherId = Auth::id();

        // Get current user's dance styles
        $existingDanceStyles = MydanceStyle::where('teacherId', $teacherId)->pluck('id', 'danceStyle')->toArray();

        foreach ($selectedDanceStyles as $selectedDanceStyle) {
            $danceLevels = isset($selectedDanceSelections[$selectedDanceStyle]) ? $selectedDanceSelections[$selectedDanceStyle] : [];

            if (isset($existingDanceStyles[$selectedDanceStyle])) {
                // Update existing dance style
                $mydance = MydanceStyle::find($existingDanceStyles[$selectedDanceStyle]);
                $mydance->danceLevel = json_encode($danceLevels);
                $mydance->save();
            } else {
                // Create new dance style
                $insertmydance = new MydanceStyle;
                $insertmydance->added_by = $teacherId;
                $insertmydance->teacherId = $teacherId;
                $insertmydance->danceStyle = $selectedDanceStyle;
                $insertmydance->danceLevel = json_encode($danceLevels);
                $insertmydance->save();
            }
        }

        // Delete unselected dance styles
        MydanceStyle::where('teacherId', $teacherId)
            ->whereNotIn('danceStyle', $selectedDanceStyles)
            ->delete();

        return redirect('my-dance-style')->with('status', 'Dance style updated successfully');
    }

}
