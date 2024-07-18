<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Models\User;

class ClassController extends Controller
{
   public function classes(Request $request)
   {

      $selecteddays = $request->input('selecteddays');
     
      if ($selecteddays) {
         // Filter classes based on the selected day
         $classes = ClassModel::with('addedBy')->where('days', $selecteddays)->get();
     } else {
         // Get all classes if no day is selected
         $classes = ClassModel::with('addedBy')->get();
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

      foreach ($input['days'] as $day) {
         $insertclass = new ClassModel;
         $insertclass->startTime = $input['start_time'];
         $insertclass->endTime = $input['end_time'];
         $insertclass->days = $day; // Store each day separately
         $insertclass->startDate = $input['start_date'];
         $insertclass->endDate = $input['end_date'];
         $insertclass->price = $input['price'];
         $insertclass->classType = 'private';
         $insertclass->studentName = $input['student_name'];
         $insertclass->added_by = Auth()->user()->id;
         $insertclass->teacherId = Auth()->user()->id;

         if (!$insertclass->save()) {
            return redirect('/classes')->with('status', 'Failed to create class for day:' . $day);
         }
      }

      return redirect('/classes')->with('status', 'Classes Created successfully');
   }

   public function destroy($classid){
      $class = ClassModel::find($classid);
      $class->delete();
      return redirect('/classes')->with('status', 'Classe Deleted successfully');
   }
}
