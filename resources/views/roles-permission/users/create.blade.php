@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <h1>Create Users
                    <a href="{{url('users')}}" class="btn btn-primary float-end">Back</a>
                    </h1>
                </div>
                <div class="card-body">
                    <form action="{{route('createuser')}}" method="POST">
                    @csrf
                        <div class="mb-3">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Email</label>
                            <input type="text" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Roles</label>
                            <select name="roles[]" id="" class="form-control" multiple>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                <option value="{{$role}}">{{$role}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="sunmit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection