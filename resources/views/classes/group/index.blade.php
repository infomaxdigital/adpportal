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
            <div class="col-sm-6">
                <h3>Manage Group Class</h3>
            </div>
            <div class="col-sm-6">
                <a href="{{url('group-class-create')}}" class="btn btn-primary float-end">Add New Group Class</a>
            </div>
        </div>

        <div class="row justify-content-center py-4">
            <div class="col-sm-12">
                <table class="table table-bordered table-striped">
                    <tbody>
                        @foreach ($classes as $class)
                            <tr>
                                
                               
                                <td>{{$class->days}}<br>{{$class->startTime}} - {{$class->endTime}}</td>
                                <td>Seat Capacity:<br>{{$class->capactiy}}</td>
                                <td>Booked Seat:<br></td>
                                <td>Available Seat:<br></td>
                                <td>
                                    <a href="{{ url('class-delete', $class->id) }}" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
        
   
</div>
@endsection