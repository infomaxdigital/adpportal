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
            <div class="col-sm-6">
                <h3>Master Dance Level</h3>
            </div>
            <div class="col-sm-6">
                <h3>Master Dance Level</h3>
            </div>

@endsection