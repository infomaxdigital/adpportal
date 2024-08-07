@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('manus') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="container m-3">
                        <a class="btn btn-primary" href="{{url('profile')}}">Profile</a>
                        <a class="btn btn-primary" href="{{url('book-private-class')}}">Book a private class</a>
                        <a class="btn btn-primary" href="{{url('book-group-class')}}">Book a group class</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Roles and Permisson') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="container m-3">
                        @can('View Role')
                            <a class="btn btn-primary" href="{{ url('roles') }}">Roles</a>
                        @endcan
                        @can('View Permission')
                            <a class="btn btn-primary" href="{{ url('permissions') }}">Permissions</a>
                        @endcan
                        @can('View User')
                            <a class="btn btn-primary" href="{{ url('users') }}">Users</a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Masters') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="container m-3">
                        @can('View Masters')
                            <a class="btn btn-primary" href="{{ url('master-dance-style') }}">Dance Styles</a>
                            <a class="btn btn-primary" href="{{ url('master-dance-level') }}">Dance Levels</a>
                            <a class="btn btn-primary" href="{{url('master-discount')}}">Discount</a>
                            <a class="btn btn-primary" href="{{url('master-membership')}}">Membership</a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Admin manus') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="container m-3">
                        @can('View Enquiries')
                            <a class="btn btn-primary" href="{{url('enquiries')}}">Enquiries</a>
                        @endcan
                        @can('View Availability')
                            <a class="btn btn-primary" href="{{url('classes')}}">Manage Availability</a>
                        @endcan
                        @can('View Group Class')
                            <a class="btn btn-primary" href="{{url('group-classes')}}">Manage Group Class</a>
                        @endcan
                        @can('View MyDancestyle')
                            <a class="btn btn-primary" href="{{url('my-dance-style')}}">My Dance Style</a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection