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
                <h3>Create Availability</h3>
            </div>
            <div class="col-sm-6">

            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <form action="{{route('class-store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Start Time(*)</label>
                                <input class="form-control" type="time" id="start_time" name="start_time" value=""
                                    required="">
                                    @error('start_time')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">End Time(*)</label>
                                <input class="form-control" type="time" id="end_time" name="end_time" value=""
                                    required="">
                                    @error('end_time')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <label>
                                        <input type="checkbox" name="days[]" value="{{ $day }}" {{ (is_array(old('days')) && in_array($day, old('days'))) ? 'checked' : '' }}>
                                        {{ $day }}
                                        @error('days')<span class="text-danger">{{$message}}</span> @enderror
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Available From (Optional)</label>
                                <input class="form-control" type="date" id="start_date" name="start_date"
                                    value="{{ old('start_date') }}">
                                    @error('start_date')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Available Upto (Optional)</label>
                                <input class="form-control" type="date" id="end_date" name="end_date"
                                    value="{{ old('end_date') }}">
                                    @error('end_date')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Student Name</label>
                                <select name="student_name" id="student_name" class="form-control">
                                    <option value="">Select Student</option>
                                    @foreach ($students as $student)
                                        <option value="{{$student->name}}">{{$student->name}}</option>
                                    @endforeach
                                </select>
                                @error('student_name')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Price(*)</label>
                                <input class="form-control" type="number" id="price" name="price" value="" min="0" required>
                                @error('price')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <a href="{{url('classes')}}" class="btn btn-primary">Cancel</a>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection