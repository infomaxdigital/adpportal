@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        @if (session('status'))
                <div class="alert alert-success">{{session('status')}}</div>

            @endif
            <div class="card">
                <div class="card-header">
                    <h1>Role: {{$role->name}}
                        <a href="{{url('roles')}}" class="btn btn-primary float-end">Back</a>
                    </h1>
                </div>
                <div class="card-body">
                    <form action="{{route('givepermissionstorole', [$role->id])}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="">Permissions</label>
                            <div class="row">
                                @foreach ($permissions as $permission)
                                
                                <div class="col-md-2">
                                    <label for="">
                                    <input type="checkbox" 
                                    name="permission[]" 
                                    class="" 
                                    value="{{$permission->name}}"
                                    {{in_array($permission->id, $rolePermissions) ? 'checked' : ''}}
                                    >
                                    {{$permission->name}}
                                    </label>
                                </div>
                                @endforeach
                            </div>
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