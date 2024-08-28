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
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            Book a private class
        </div>
    </div>
</div>
<div class="container">
    <h1>Book a Private Class</h1>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form action="#" method="GET" id="filterForm">
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
                                <p class="filter-content" data-level="{{ $levelId }}" data-styles="{{ implode(', ', $styles) }}">
                                    {{ $danceLevelNames[$levelId] ?? 'Unknown Level' }} - {{ implode(', ', $styles) }}
                                </p>
                            @endforeach
                        @else
                            <p>No dance styles available</p>
                        @endif
                        <a href="#" class="btn btn-primary booking-btn" data-bookingbtn-id="{{ $allDay->teacherId }}">Make a
                            Booking</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4" id="data1"></div>
        <div class="col-md-8" id="data2"></div>
    </div>
    <!-- Booking Form Container -->
    <div class="row justify-content-center">
        <div id="bookingFormContainer" class="mt-4 col-md-12" style="display:block;">
            <form id="bookingForm">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <label for="teacherName" class="form-label">Teacher Name:</label>
                                <div class="teacherName"></div>
                                <input type="hidden" class="form-control" id="teacherName" value="">
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
                                    <label class="col-form-label pt-0" for="">No. of Sessions ($10 per session)</label>
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
                                <script>
                                    var membershipDiscount = {{$membershipDiscountAmount}}
                                </script>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Booking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection