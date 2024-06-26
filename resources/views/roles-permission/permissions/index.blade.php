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
                    <h1>Permissions
                        @can('Create Permission')
                            <a href="{{url('permission-create')}}" class="btn btn-primary float-end">Add Permission</a>
                        @endcan
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
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{$permission->name}}</td>
                                    <td>
                                        @can('Edit Permission')
                                            <a href="{{ url('permissionedit', $permission->id) }}"
                                                class="btn btn-success">Edit</a>
                                        @endcan
                                        @can('Delete Permission')
                                            <a href="{{ url('deletepermission', $permission->id) }}"
                                                class="btn btn-danger">Delete</a>
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