@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <h1>Create Permission
                    <a href="{{url('permissions')}}" class="btn btn-primary float-end">Back</a>
                    </h1>
                </div>
                <div class="card-body">
                    <form action="{{route('createpermission')}}" method="POST">
                    @csrf
                        <div class="mb-3">
                            <label for="">Permission Name</label>
                            <input type="text" name="name" class="form-control">
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