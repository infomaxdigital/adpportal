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
                            <td>
                                <div class="modal fade" id="addtoportal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add To Portal</h5>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{url('createuser')}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="col-form-label pt-0" for="">Name(*)</label>
                                                                <input class="form-control" type="text" name="name"
                                                                    placeholder="Student Name" required=""
                                                                    value="{{$enquiry_user->name}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label pt-0" for="">Email(*)</label>
                                                                <input class="form-control" type="text" name="email"
                                                                    placeholder="Student Email" required=""
                                                                    value="{{$enquiry_user->email}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label pt-0" for="">Mobile(*)</label>
                                                                <input class="form-control" type="text" name="mobile"
                                                                    placeholder="Student Mobile" required=""
                                                                    value="{{$enquiry_user->mobile}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label pt-0" for="">Dance
                                                                    Style(*)</label>
                                                                <select name="dancestyle[]" id="" class="form-control"
                                                                    multiple>
                                                                    <option value="">Select Dance Style</option>
                                                                    @foreach ($dancestyles as $dancestyle)
                                                                        <option value="{{$dancestyle}}">{{$dancestyle}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label pt-0" for="">Dance
                                                                    Level(*)</label>
                                                                <select name="dancelevel" id="" class="form-control">
                                                                    <option value="">Select Dance Level</option>
                                                                    @foreach ($dancelevels as $dancelevel)
                                                                        <option value="{{$dancelevel}}">{{$dancelevel}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label pt-0" for="">Membership</label>
                                                                <select name="membership" id="" class="form-control">
                                                                    @foreach ($memberships as $membership)
                                                                        <option value="{{$membership->membershipId}}">
                                                                            {{$membership->membershipName}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="hidden" name="role_type" value="static">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button class="btn btn-primary" type="submit">Add</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addtoportal" class="btn btn-success">Add
                                    To Portal</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection