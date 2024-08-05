<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\masterdancelevel;
use App\Models\masterdancestyle;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
   function __construct()
    {
         $this->middleware('permission:View Availability', ['only' => ['classes']]);
         $this->middleware('permission:Create Availability', ['only' => ['create','store']]);
         $this->middleware('permission:Delete Availability', ['only' => ['destroy']]);
         $this->middleware('permission:View Group Class', ['only' => ['groupclasses']]);
         $this->middleware('permission:Create Group Class', ['only' => ['groupclasscreate','store']]);
         $this->middleware('permission:Delete Group Class', ['only' => ['destroy']]);
         
    }
   public function classes(Request $request)
   {
      $selecteddays = $request->input('selecteddays');
      $query = ClassModel::with('teacher')
         ->where('teacherId', Auth::id())
         ->where('classType', 'private');
      if ($selecteddays) {
         // Filter classes based on the selected day
         $query->where('days', $selecteddays);
      } else {
         // Get all classes if no day is selected
         $classes = $query->get();
      }
      return view('classes.private.index', compact('classes', 'selecteddays'));
   }

   public function create()
   {
      $students = User::role('Student')->get();
      return view('classes.private.create', compact('students'));
   }
   public function store(Request $request)
   {
      $request->validate([
         'start_time' => 'required|date_format:H:i',
         'end_time' => 'required|date_format:H:i|after:start_time',
         'days' => 'required|array',
         'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
         'start_date' => 'nullable|date',
         'end_date' => 'nullable|date|after_or_equal:start_date',
         'student_name' => 'nullable|string',
         'price' => 'required|numeric|min:0',
      ]);

      $input = $request->all();
      //  $userId = Auth::user()->id;
      $redirectRoute = '/classes'; // Default redirect route

      foreach ($input['days'] as $day) {
         $insertclass = new ClassModel;
         $insertclass->startTime = $input['start_time'];
         $insertclass->endTime = $input['end_time'];
         $insertclass->days = $day; // Store each day separately
         $insertclass->startDate = $input['start_date'];
         $insertclass->endDate = $input['end_date'];
         $insertclass->price = $input['price'];
         $insertclass->classType = $input['class_type'];
         $insertclass->added_by = Auth()->user()->id;
         $insertclass->teacherId = Auth()->user()->id;

         if ($input['class_type'] == 'private') {
            $insertclass->studentName = $input['student_name'];
            $redirectRoute = '/classes';
         }

         if ($input['class_type'] == 'group') {
            $insertclass->capactiy = $input['capacity'];
            $insertclass->danceLevel = $input['dance_level'];
            $insertclass->danceStyle = json_encode($input['dance_styles']);
            // $insertmydance->danceLevel = json_encode($danceLevels);
            // Handle dance styles and levels
            // if (isset($input['dance_styles'])) {

            // }
            // if (isset($input['dance_styles'])) {
            //    $danceSelection = [];
            //    foreach ($input['dance_styles'] as $dancestyleId) {
            //       $danceSelection[$dancestyleId] = $input['dance_selection'][$dancestyleId] ?? [];
            //    }
            //    $insertclass->danceStyleLevel = json_encode($danceSelection); // Save as JSON
            // }
            $redirectRoute = '/group-classes';
         }

         if (!$insertclass->save()) {
            return redirect('/classes')->with('status', 'Failed to create class for day:' . $day);
         }
      }

      return redirect($redirectRoute)->with('status', 'Classes Created successfully');
   }

   public function destroy($classid)
   {
      $class = ClassModel::find($classid);
      // Retrieve the class type before deleting
      $classType = $class->classType;
      $class->delete();
      if ($classType == 'group') {
         return redirect('/group-classes')->with('status', 'Class deleted successfully');
      } elseif ($classType == 'private') {
         return redirect('/classes')->with('status', 'Class deleted successfully');
      } else {
         return redirect('/classes')->with('status', 'Class deleted successfully');
      }
   }
   public function groupclasses()
   {
      $classes = ClassModel::with('teacher')
         ->where('teacherId', Auth::id())
         ->where('classType', 'group')
         ->get();
      // Initialize $danceStyleLevels as an empty array
      $selectedDanceStyles = [];
      $danceStyles = masterdancestyle::pluck('dancestyleName', 'dancestyleId')->all();
      $danceLevels = masterdancelevel::pluck('dancelevelName', 'dancelevelId')->all();
      foreach ($classes as $class) {
         $selectedDanceStyles = json_decode($class['danceStyle'], true);
         // Convert selected dance styles to comma-separated string
         $class->selectedDanceStylesString = implode(', ', array_map(fn($styleId) => $danceStyles[$styleId] ?? 'Unknown', $selectedDanceStyles));
      }
      //   echo "<pre>";
      //   print_r($selectedDanceStyles);
      //   echo "<pre>";
      return view('classes.group.index', compact('classes', 'danceStyles', 'danceLevels', 'selectedDanceStyles'));
   }
   public function groupclasscreate()
   {
      $dancestyles = masterdancestyle::all();
      $dancelevels = masterdancelevel::all();
      return view('classes.group.create', compact('dancestyles', 'dancelevels'));
   }
   public function groupclassview($id)
   {
      $class = ClassModel::findOrFail($id); // Replace ClassModel with your actual model name
      //print_r($class); exit;
      $danceStyles = masterdancestyle::pluck('dancestyleName', 'dancestyleId')->all();
      $danceLevels = masterdancelevel::pluck('dancelevelName', 'dancelevelId')->all();
      $selectedDanceStyles = json_decode($class['danceStyle'], true);
      $class->selectedDanceStylesString = implode(', ', array_map(fn($styleId) => $danceStyles[$styleId] ?? 'Unknown', $selectedDanceStyles));
      return view('classes.group.view', compact('class','danceLevels')); // 'class-view' is the view file that will display the class details
   }

}
