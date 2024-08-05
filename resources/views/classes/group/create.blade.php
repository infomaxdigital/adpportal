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
            <div class="col-sm-12">
                <h3>Add New Group Class</h3>
            </div>
        </div>
    </div>
</div>
@can('Create Group Class');
<div class="container">
    <div class="page-body">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <form action="{{route('class-store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="days">Day:</label>
                                <select name="days[]" id="days" class="form-control">
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <option value="{{ $day }}" {{ old('days') == $day ? 'selected' : '' }}>{{ $day }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('days')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Start Time(*)</label>
                                <input class="form-control" type="time" id="start_time" name="start_time"
                                    value="{{ old('start_time', '09:00') }}" required="">
                                @error('start_time')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">End Time(*)</label>
                                <input class="form-control" type="time" id="end_time" name="end_time"
                                    value="{{ old('start_time', '09:45') }}" required="">
                                @error('end_time')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="row justify-content-center mb-3">
                            <div class="col-sm-12">
                                <label for="dance_level" class="form-label">Dance Level</label>
                                <div class="form-check form-check-inline">
                                    <select class="form-control" id="dance_level" name="dance_level">
                                        @foreach ($dancelevels as $dancelevel)
                                            <option value="{{ $dancelevel->dancelevelId }}">
                                                {{ $dancelevel->dancelevelName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mb-3">
                            <div class="col-sm-12">
                                <p>Dance Style</p>
                            </div>
                        </div>
                        @foreach ($dancestyles as $dancestyle)
                            <div class="row justify-content-center mb-3">
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dance_styles[]"
                                            value="{{ $dancestyle->dancestyleId }}"
                                            id="dancestyle_{{ $dancestyle->dancestyleId }}" {{ isset($mydancestyles[$dancestyle->dancestyleId]) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dancestyle_{{ $dancestyle->dancestyleId }}">
                                            {{ $dancestyle->dancestyleName }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Capacity(*)</label>
                                <input class="form-control" type="number" id="capacity" name="capacity" value="" min="0"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Price</label>
                                <input class="form-control" type="number" id="price" name="price" value="" min="0">
                                @error('price')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">Start Date (Optional)</label>
                                <input class="form-control" type="date" id="start_date" name="start_date"
                                    value="{{ old('start_date') }}">
                                @error('start_date')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label pt-0" for="">End Date (Optional)</label>
                                <input class="form-control" type="date" id="end_date" name="end_date"
                                    value="{{ old('end_date') }}">
                                @error('end_date')<span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                        <input type="hidden" id="class_type" name="class_type" value="group">
                    </div>
                    <a href="{{url('group-classes')}}" class="btn btn-primary">Cancel</a>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection