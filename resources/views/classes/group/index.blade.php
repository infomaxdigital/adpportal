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
@can('View Group Class')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <h3>Manage Group Class</h3>
            </div>
            @can('Create Group Class');
                <div class="col-sm-6">
                    <a href="{{url('group-class-create')}}" class="btn btn-primary float-end">Add New Group Class</a>
                </div>
            @endcan
        </div>
        <div class="row justify-content-center py-4">
            <div class="col-sm-12">
                <table class="table table-bordered table-striped">
                    <tbody>
                        @foreach ($classes as $class)
                            <tr>
                                <td>{{ $danceLevels[$class->danceLevel] }}<br>{{ $class->selectedDanceStylesString }}</td>
                                <td>{{$class->days}}<br>{{$class->startTime}} - {{$class->endTime}}</td>
                                <td>Seat Capacity:<br>{{$class->capactiy}}</td>
                                <td>Booked Seat:<br></td>
                                <td>Available Seat:<br></td>
                                <td>
                                    <a href="{{ route('group-class-view', $class->id) }}" class="btn btn-info">View</a>
                                    @can('Delete Group Class')<a href="{{ url('class-delete', $class->id) }}" class="btn btn-danger">Delete</a>@endcan
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endcan
@endsection