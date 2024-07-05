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
            <!-- Create dance Level start -->
            <div class="col-sm-6">
                <div class="modal fade" id="insertmodel" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Dance Level Create</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{route('master-dancelevel-create')}}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-form-label pt-0" for="">Dance Level Name
                                                    (*)</label>
                                                <input class="form-control" type="text" name="dancelevelname"
                                                    placeholder="Dance Level Name" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button"
                                        data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" type="submit">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bookmark">
                    <button class="btn btn-square btn-primary btn-xs" type="button" data-bs-toggle="modal"
                        data-bs-target="#insertmodel"><i class="fa fa-plus-circle"></i> Create Dance
                        Level</button>
                </div>
            </div>
            <!-- Create dance Level end -->
        </div>
    </div>
</div>
<div class="container">
    <div class="page-header">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Dance Level Name</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dancelevels as $dancelevel)
                            <tr>
                                <td>{{$dancelevel->dancelevelName}}</td>
                                <td>{{$dancelevel->addedby->name}}</td>
                                <td>{{ $dancelevel->lastupdatedby->name ?? 'N/A' }}</td>
                                <!-- edit dance Level start -->
                                <td>
                                    <div class="modal fade" id="danceleveledit{{$dancelevel->dancelevelId}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalCenter" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Dance Level Edit</h5>
                                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{url('master-dancelevel-update')}}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-form-label pt-0" for="">Dance Level
                                                                        Name
                                                                        (*)</label>
                                                                    <input class="form-control" type="text"
                                                                        name="dancelevelname" placeholder="Dance Level Name" value="{{$dancelevel->dancelevelName}}"
                                                                        required="">
                                                                        <input type="hidden" name="dancelevelid" value="{{$dancelevel->dancelevelId}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#danceleveledit{{$dancelevel->dancelevelId}}">Edit</a>
                                </td>
                                <!-- edit dance level end -->
                                <!-- status dance level start -->
                                <td>
                                <div class="modal fade" id="statusmodal{{$dancelevel->dancelevelId}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title">Dance Level Status</h5>
                                          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{route('master-dancelevel-chnagestatus')}}" method="post">
                                          @csrf
                                          <div class="modal-body">
                                            @if($dancelevel->dancelevelStatus == '1')
                                            <p>{{$dancelevel->dancelevelName}} is in <span class=" ">Active</span> status</p>
                                            <p>Do you realy want to <span class=" ">De-active</span> Dance Level </p>
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="dancelevelid" value="{{$dancelevel->dancelevelId}}">
                                            @else
                                            <p>{{$dancelevel->dancelevelName}} is in <span class=" ">De-active</span> status</p>
                                            <p>Do you realy want to <span class=" ">Active</span> Dance Level </p>
                                            <input type="hidden" name="status" value="1">
                                            <input type="hidden" name="dancelevelid" value="{{$dancelevel->dancelevelId}}">
                                            @endif
                                          </div>
                                          <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit">Yes</button>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                    @if ($dancelevel->dancelevelStatus == '1')
                                    <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#statusmodal{{$dancelevel->dancelevelId}}">Active</a>
                                    @else
                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#statusmodal{{$dancelevel->dancelevelId}}">deactivate</a>
                                    @endif
                                </td>
                                <!-- status dance level end -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection