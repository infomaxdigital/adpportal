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
    <div class="page-header">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <h3>Manage Availablility</h3>
            </div>
            <div class="col-sm-6">
                <a href="{{url('class-create')}}" class="btn btn-primary float-end">Create Availablility</a>
            </div>
        </div>
        <div class="row justify-content-center py-3">
            <div class="col-sm-12">
                <form id="daySelectionForm" method="GET" action="{{ url('classes') }}">
                    <div class="form-group">
                        <select class="form-control" id="selecteddays" name="selecteddays">
                        <option value="">All Days</option>
                            <option value="Monday" {{('Monday' == $selecteddays) ? 'selected':''}}>Monday</option>
                            <option value="Tuesday" {{('Tuesday' == $selecteddays) ? 'selected':''}}>Tuesday</option>
                            <option value="Wednesday" {{('Wednesday' == $selecteddays) ? 'selected':''}}>Wednesday</option>
                            <option value="Thursday" {{('Thursday' == $selecteddays) ? 'selected':''}}>Thursday</option>
                            <option value="Friday" {{('Friday' == $selecteddays) ? 'selected':''}}>Friday</option>
                            <option value="Saturday" {{('Saturday' == $selecteddays) ? 'selected':''}}>Saturday</option>
                            <option value="Sunday" {{('Sunday' == $selecteddays) ? 'selected':''}}>Sunday</option>
                        </select>
                        <!-- <button type="submit" class="btn btn-primary mt-2">Filter</button> -->
                    </div>
                </form>
            </div>
        </div>
        <div class="row justify-content-center py-4">
            <div class="col-sm-12">
                <table class="table table-bordered table-striped">
                    <tbody>
                        @foreach ($classes as $class)
                            <tr>
                                <td>{{$class->startTime}} to {{$class->endTime}}</td>
                                <td>Not Booked</td>
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
</div>
@endsection