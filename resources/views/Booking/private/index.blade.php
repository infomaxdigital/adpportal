@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success">{{session('status')}}</div>
            @endif
        </div>
    </div>
</div>
@if($classType == 'private')
    <h2>Private Classes</h2>
   
@endif

@if($classType == 'group')
    <h2>Group Classes</h2>
  
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if($classType == 'private')
            Book a private class
            @elseif($classType == 'group')
            Book a Group class
            @endif
        </div>
    </div>
</div>
<div class="container">
    <!-- Steps Indicator -->
    <div class="steps-indicator">
        <div class="step" id="step-1">
            <div class="step-number">1</div>
        </div>
        <div class="step" id="step-2">
            <div class="step-number">2</div>
        </div>
        <div class="step" id="step-3">
            <div class="step-number">3</div>
        </div>
        <div class="step" id="step-4">
            <div class="step-number">4</div>
        </div>
    </div>

    <!-- Existing HTML content -->
    <!-- Your step content goes here -->
</div>

<div class="container">
    <!-- Step 1 start -->
     @if($classType == 'private')
    <div class="wizard-step" id="step1">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <form action="#" id="filterForm">
                    @csrf
                    <!-- Dance Style Dropdown -->
                    <div class="form-group">
                        <label for="dance_style">Select Dance Style</label>
                        <select name="dance_style" id="dance_style" class="form-control">
                            <option value="">Select Dance Style</option>
                            @foreach($allDanceStyle as $style)
                                <option value="{{ $style->dancestyleId }}">{{ $style->dancestyleName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Dance Level Dropdown -->
                    <div class="form-group">
                        <label for="dance_level">Select Dance Level</label>
                        <select name="dance_level" id="dance_level" class="form-control">
                            <option value="">Select Dance Level</option>
                            @foreach($allDanceLevel as $level)
                                <option value="{{ $level->dancelevelId }}">{{ $level->dancelevelName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Clear Selection Link -->
                    <div class="form-group">
                        <a href="#" id="clear-selection" class="btn btn-secondary">Clear Selection</a>
                    </div>
                    <!-- <button type="submit" class="btn btn-primary">Book Class</button> -->
                </form>
            </div>
            <div class="col-md-8">
                <div class="row justify-content-center" id="filterableBlocks">
                    @foreach ($allDays as $allDay)
                        <div class="col-md-12 mb-4 filter-block" data-teacher="{{ $allDay->teacherId }}"
                            data-days="{{ $allDay->days }}" data-teachername="{{ $allDay->teacherName }}">
                            <strong>Teacher Name:</strong> {{$allDay->teacherName}}<br>
                            <strong>Days:</strong> {{$allDay->days}}<br>

                            @if (isset($groupedData[$allDay->teacherId]))
                                @foreach ($groupedData[$allDay->teacherId] as $levelId => $styles)
                                    <p class="filter-content" data-level="{{ $levelId }}"
                                        data-styles="{{ implode(', ', $styles) }}">
                                        {{ $danceLevelNames[$levelId] ?? 'Unknown Level' }} - {{ implode(', ', $styles) }}
                                    </p>
                                @endforeach
                            @else
                                <p>No dance styles available</p>
                            @endif
                            <a href="#" class="btn btn-primary booking-btn"
                                data-bookingbtn-id="{{ $allDay->teacherId }}">Make a
                                Booking</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Step 1 end -->
    <!-- Step 2 start -->
    <div class="wizard-step" id="step2" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-md-4" id="data1"></div>
            <div class="col-md-8" id="data2"></div>
        </div>
    </div>
    <!-- Step 2 end -->
    <!-- Step 3 start -->
    <!-- Booking Form Container -->
    <div class="wizard-step" id="step3" style="display:none;">
        <div class="row justify-content-center">
            <div id="bookingFormContainer" class="mt-4 col-md-12" style="display:block;">
                <form id="bookingForm">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="row justify-content-center">
                                <div class="col-md-4">
                                    <label for="teacherName" class="form-label">Teacher Name:</label>
                                    <div class="teacherName"></div>
                                    <input type="hidden" class="form-control" id="teacherName" value="">
                                    <input type="hidden" class="form-control" id="teacherId" value="">
                                </div>
                                <div class="col-md-4">
                                    <label for="days" class="form-label">Day</label>
                                    <div class="days"></div>
                                    <input type="hidden" class="form-control" id="days" value="">
                                </div>
                                <div class="col-md-4">
                                    <label for="timeslot" class="form-label">Time Slot:</label>
                                    <div id="timeSlot"></div>
                                    <input type="hidden" class="form-control" id="startTime" value="">
                                    <input type="hidden" class="form-control" id="endTime" value="">
                                    <input type="hidden" name="classId" id="classId" value="">
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-4">
                                    <label for="startDate" class="form-label">Start Date:</label>
                                    <div class="startDate"></div>

                                    <input type="hidden" class="form-control" id="startDate" value="">
                                </div>
                                <div class="col-md-4">
                                    <div class="frequency">Frequency</div>
                                    <div class="form-group">
                                        <input type="radio" id="weekly" name="frequency" value="weekly">
                                        <label for="weekly">Weekly</label>
                                        <input type="radio" id="fortnightly" name="frequency" value="fortnightly">
                                        <label for="fortnightly">Fortnightly</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="noOfStudents">No. of Students</div>
                                    <div class="form-group">
                                        <input type="radio" id="single" name="noofstudents" value="single">
                                        <label for="single">Single</label>

                                        <input type="radio" id="couple" name="noofstudents" value="couple">
                                        <label for="couple">Couple</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="partnername" name="partnername"
                                            placeholder="Enter Your Partner Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0" for="">No. of Sessions ($10 per
                                            session)</label>
                                        <input class="form-control" type="text" name="noofsessions" id="noofsessions"
                                            placeholder="Enter Number of Sessions" required="">
                                        <!-- Popup Structure -->
                                        <div class="bulkdiscount">Bulk Discount Available</div>
                                        <div id="bulkDiscountPopup" class="popup" style="display:none;">
                                            <div class="popup-content">
                                                <span class="close-popup">&times;</span>
                                                <h5>Bulk Discount Details</h5>
                                                <div>Bulk Discount</div>
                                                <ul id="discountList">
                                                    @foreach ($allDiscount as $Discount)
                                                        <li>Book {{$Discount->minSessions}} or More Sessions - Get
                                                            {{$Discount->discountAmount}}% Discount
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <script>
                                                    var discounts = @json($allDiscount);
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <h5>Billing Summary</h5>
                                    <div>
                                        <strong>Final Amount:</strong> <span id="finalAmount">$0.00</span>
                                    </div>
                                    <div>
                                        <strong>Membership Discount:</strong> <span
                                            id="membershipDiscount">${{ $membershipDiscountAmount }}</span>
                                    </div>
                                    <input type="hidden" name="final_amount" id="finalAmountInput" value="0.00">
                                    <input type="hidden" name="total_discount" id="totalDiscountInput" value="0.00">
                                    <input type="hidden" name="total_amount" id="totalAmountInput" value="0.00">
                                    <input type="hidden" name="studentName" id="studentName" value="{{$user->name}}">
                                    <input type="hidden" name="studentEmail" id="studentEmail" value="{{$user->email}}">
                                    <input type="hidden" name="studentPhone" id="studentPhone"
                                        value="{{$user->contact}}">
                                    <input type="hidden" name="studentId" id="studentId" value="{{$user->id}}">
                                    <input type="hidden" class="form-control" id="endDate" value="">
                                    <script>
                                        var membershipDiscount = {{$membershipDiscountAmount}}
                                    </script>
                                </div>
                            </div>
                            <!-- Stripe Card Element -->
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div id="card-element"></div>
                                    <div id="card-errors" role="alert"></div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-primary backbtn2">Back</a>
                            <button id="submit-button" type="submit" class="btn btn-primary">Submit Booking</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Step 3 end -->
    <!-- Step 4 start -->
    <div class="wizard-step" id="step4" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Booking Confirmed</h2>
                <div id="bookingDetails"></div>
            </div>
            <div class="col-md-6">
                <h2>Schedule</h2>
                <div id="scheduleOutput"></div>
                <a href="#" class="btn btn-primary">Back to Current Booking</a>
            </div>
        </div>
    </div>
    <!-- Step 4 end -->
</div>
</div>
<script src="https://js.stripe.com/v3/"></script>
@endsection