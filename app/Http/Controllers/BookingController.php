<?php

namespace App\Http\Controllers;

use App\Models\BookingModel;
use App\Models\MydanceStyle;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\masterdancestyle;
use App\Models\masterdancelevel;
use App\Models\ClassModel;
use App\Models\masterdiscounts;

class BookingController extends Controller
{
    public function bookPrivateClass($classType)
    {
        $user = Auth::user();
        $availDanceStyle = explode(",", $user->dancestyle);
        $availDanceLevel = $user->dancelevel;
        $allDanceStyle = masterdancestyle::all();
        $allDanceLevel = masterdancelevel::all();
        $allDiscount = masterdiscounts::all();
        $allDaysPrivate = ClassModel::join('users', 'classes.teacherId', '=', 'users.id')
            ->groupBy('teacherId', 'users.name')
            ->selectRaw('teacherId,users.name as teacherName, GROUP_CONCAT(DISTINCT days ORDER BY FIELD(days, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")) as days')
            ->where('classType', $classType)
            ->get();

        $allDaysGroup = ClassModel::join('users', 'classes.teacherId', '=', 'users.id')
            ->select('classes.*', 'users.name as teacherName')
            ->where('classType', $classType)
            ->orderByRaw('FIELD(days, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->get();

        $membershipDiscountAmount = $user->membership ? $user->membership->membershipDiscountAmount : 'No Membership';
        // $user = User::find(14);
        // $membership = $user->membership;

        // dd($membership);

        //echo $membershipName; exit;

        $teacherIds = $allDaysPrivate->pluck('teacherId')->toArray(); // Convert collection to array
        // Fetch all records from the mydancestyle table
        $danceStyles = MyDanceStyle::all();

        // Initialize arrays to hold the names
        $danceStyleNames = MasterDanceStyle::pluck('dancestyleName', 'dancestyleId')->toArray();
        $danceLevelNames = MasterDanceLevel::pluck('dancelevelName', 'dancelevelId')->toArray();

        //print_r($danceStyleNames); exit;
        // Initialize an array to hold the grouped data
        $groupedData = [];

        foreach ($danceStyles as $style) {
            // Convert danceLevel JSON string to an array
            $levels = json_decode($style->danceLevel, true);

            // Ensure json_decode successfully converted it to an array
            if (!is_array($levels)) {
                \Log::debug('Dance level is not an array:', ['levels' => $levels]);
                continue; // Skip processing this entry if it's not a valid array
            }

            // Group by teacherId
            if (!isset($groupedData[$style->teacherId])) {
                $groupedData[$style->teacherId] = [];
            }

            // Add the danceStyle under each danceLevel with names
            foreach ($levels as $level) {
                if (!isset($groupedData[$style->teacherId][$level])) {
                    $groupedData[$style->teacherId][$level] = [];
                }
                if (!in_array($style->danceStyle, $groupedData[$style->teacherId][$level])) {
                    $groupedData[$style->teacherId][$level][] = $style->danceStyle;
                }
            }
        }
        // Convert dance style IDs to names
        foreach ($groupedData as $teacherId => $levels) {
            foreach ($levels as $levelId => $styles) {
                $groupedData[$teacherId][$levelId] = array_map(function ($styleId) use ($danceStyleNames) {
                    return $danceStyleNames[$styleId] ?? 'Unknown Style';
                }, $styles);
            }
        }

        $stripePublishableKey = config('stripe.stripe_pk');
        //echo $stripePublishableKey; exit;

        // group class dancestyle logic
        foreach ($allDaysGroup as $alldaygroupStyle) {
            $groupStyleIds = json_decode($alldaygroupStyle->danceStyle, true);
            // Map the IDs to their corresponding names
            if (is_array($groupStyleIds)) {
                $alldaygroupStyle->danceStyleNames = array_map(function ($id) use ($danceStyleNames) {
                    return $danceStyleNames[$id] ?? 'Unknown Style';
                }, $groupStyleIds);
            }
        }
        // Get booking info gro group class
        $bookings = BookingModel::where('classType', 'group')
            ->get(['classId', 'endDate']);

        // Fetch class capacities
        $classes = ClassModel::whereIn('id', $bookings->pluck('classId'))
            ->get(['id', 'capacity']);

        $bookedClasses = [];
        foreach ($bookings as $booking) {
            $bookedClasses[] = [
                'classId' => $booking->classId,
                'endDate' => $booking->endDate,
                'capacity' => null, // Initialize capacity
            ];
        }

        // Merge capacity data with booking data

        foreach ($classes as $class) {
            foreach ($bookedClasses as &$bookedClass) {
                if ($bookedClass['classId'] == $class->id) {
                    $bookedClass['capacity'] = $class->capacity;
                }
            }
        }
        //  dd($bookings);


        // dd($bookedClasses);

        if ($classType === 'private') {
            return view('Booking.private.index', compact('user', 'allDanceStyle', 'allDanceLevel', 'allDaysPrivate', 'allDaysGroup', 'groupedData', 'danceLevelNames', 'danceStyleNames', 'allDiscount', 'membershipDiscountAmount', 'stripePublishableKey', 'classType'));
            //return view('private_classes_view', compact('availDanceStyle', 'availDanceLevel', 'allDanceStyle', 'allDanceLevel', 'allDiscount', 'allDaysPrivate'));
        }
        if ($classType === 'group') {
            return view('Booking.group.index', compact('user', 'allDanceStyle', 'allDanceLevel', 'allDaysPrivate', 'allDaysGroup', 'groupedData', 'danceLevelNames', 'danceStyleNames', 'allDiscount', 'membershipDiscountAmount', 'stripePublishableKey', 'classType', 'bookedClasses'));
        }

    }
    public function getClassesByTeacher($teacherId)
    {
        // Retrieve the class type from the route defaults
        // Fetch classes based on the teacher ID
        $classes = ClassModel::where('teacherId', $teacherId)
            ->where('classType', 'private')
            ->get();

        // Fetch booked class IDs
        $bookings = BookingModel::get(['classId', 'endDate']);

        $bookedClasses = [];
        foreach ($bookings as $booking) {
            $bookedClasses[] = [
                'classId' => $booking->classId,
                'endDate' => $booking->endDate,
            ];
        }

        // Return the data as JSON
        return response()->json([
            'classes' => $classes,
            'bookedClasses' => $bookedClasses
        ]);
    }

    public function getClassesBySlot($slotId)
    {
        $classes = ClassModel::where('classes.id', $slotId)
            ->join('users', 'users.id', '=', 'classes.teacherId')
            ->select('classes.*', 'users.name as teacherName')
            ->get();

        // Return the data as JSON
        return response()->json($classes);
    }

}
