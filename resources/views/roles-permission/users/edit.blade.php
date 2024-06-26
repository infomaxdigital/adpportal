@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <h1>Edit Users
                    <a href="{{url('users')}}" class="btn btn-primary float-end">Back</a>
                    </h1>
                </div>
                <div class="card-body">
                    <form action="{{url('updateuser/'.$user->id)}}" method="POST">
                    @csrf
                        <div class="mb-3">
                            <label for="">Name</label>
                            <input type="text" name="name" value="{{$user->name}}" class="form-control">
                            @error('name')<span class="text-danger">{{$message}}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="">Email</label>
                            <input type="text" name="email" readonly value="{{$user->email}}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Password</label>
                            <input type="text" name="password" class="form-control">
                            @error('password')<span class="text-danger">{{$message}}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="">Roles</label>
                            <select name="roles[]" id="" class="form-control" multiple>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                <option value="{{$role}}" {{in_array($role,$userRoles) ? 'selected':''}} >{{$role}}</option>
                                @endforeach
                            </select>
                            @error('roles')<span class="text-danger">{{$message}}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <button type="sunmit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection