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
            <h3>My Profile</h3>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-4">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            @if ($membership)
                <p><strong>Membership:</strong> {{ $membership->membershipName }}</p>
            @else
                <p><strong>Membership:</strong> No membership details available.</p>
            @endif
        </div>
        <div class="col-sm-8">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <h3>Personal Profile</h3>
                </div>
            </div>
            <form method="POST" action="{{ route('my-profile-store', $user->id) }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="contact">Phone Number:</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="{{ $user->contact }}"
                        required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection