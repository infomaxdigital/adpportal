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
                    <h1>List of
                       
                            <a href="{{url('role-create')}}" class="btn btn-primary float-end">Add Roles</a>
                       
                    </h1>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{$role->name}}</td>
                                    <td>

                                        <a href="{{ url('givepermissions', $role->id) }}" class="btn btn-success">Add/Edit
                                            Role Permission</a>

                                        @can('Edit Role')
                                            <a href="{{ url('roleedit', $role->id) }}" class="btn btn-success">Edit</a>
                                        @endcan
                                        @can('Delete Role')
                                            <a href="{{ url('deleterole', $role->id) }}" class="btn btn-danger">Delete</a>
                                        @endcan
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