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
        <div class="col-md-12">
             <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach ($enquiry_users as $enquiry_user)
							<tr>
								<td>{{$enquiry_user->name}}</td>
								<td>{{$enquiry_user->email}}</td>
								<td>{{$enquiry_user->mobile}}</td>
								<td>{{$enquiry_user->message}}</td>
								<td>Add to portal</td>
							</tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</div>
@endsection
