@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('roles-permission.nav-links')
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success">{{session('status')}}</div>

            @endif
            <div class="card">
                <div class="card-header">
                    <h1>Users
                        <a href="{{url('users-create')}}" class="btn btn-primary float-end">Add User</a>
                    </h1>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $getrolename)
                                            <label for="" class="badge bg-primary mx-1">{{$getrolename}}</label>
                                            @endforeach
                                        @endif
                                    </td>
                                        <td>
                                        <a href="{{ url('useredit', $user->id) }}" class="btn btn-success">Edit</a>
                                        <a href="{{ url('deleteuser', $user->id) }}" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection