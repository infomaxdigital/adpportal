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
        <div class="col-sm-12">
            <h3>View Group Class</h3>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm-6">
        <table class="table table-bordered table-striped">
        <tbody>
           <tr><td><b>Day:</b> {{$class->days}}</td></tr>
           <tr><td><b>Time Slot:</b> {{$class->startTime}} - {{$class->endTime}} </td></tr>
           <tr><td><b>Dance Style:</b> {{ $class->selectedDanceStylesString}} </td></tr>
           <tr><td><b>Dance Level:</b> {{$danceLevels[$class->danceLevel]}} </td></tr>
           <tr><td><b>Capacity:</b> {{$class->capactiy}}</td></tr>
           <tr><td><b>Booked:</b> </td></tr>
           <tr><td><b>Available:</b> </td></tr>
           </tbody>
           </table>
        </div>
        <div class="col-sm-6">
        <table class="table table-bordered table-striped">
        <tbody>
            <th>Student Name</th>
            <th>Renewal Date</th>
            </tbody>
            </table>
        </div>
    </div>
</div>

@endsection