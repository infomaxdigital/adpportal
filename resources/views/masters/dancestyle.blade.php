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
                <h3>Master Dance Style</h3>
            </div>
            <!-- Create dance style start -->
            @can('Create Masters')
            <div class="col-sm-6">
                <div class="modal fade" id="insertmodel" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Dance Style Create</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{route('master-dancestyle-create')}}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-form-label pt-0" for="">Dance Style Name
                                                    (*)</label>
                                                <input class="form-control" type="text" name="dancestylename"
                                                    placeholder="Dance Style Name" required="">
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
                        Style</button>
                </div>
            </div>
            @endcan
            <!-- Create dance style end -->
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
                            <th>Dance style Name</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dancestyles as $dancestyle)
                            <tr>
                                <td>{{$dancestyle->dancestyleName}}</td>
                                <td>{{$dancestyle->addedby->name}}</td>
                                <td>{{ $dancestyle->lastupdatedby->name ?? 'N/A' }}</td>
                                <!-- edit dance style start -->
                                @can('Edit Masters')
                                <td>
                                    <div class="modal fade" id="dancestyleedit{{$dancestyle->dancestyleId}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalCenter" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Dance Style Edit</h5>
                                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{url('master-dancestyle-update')}}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-form-label pt-0" for="">Dance Style
                                                                        Name
                                                                        (*)</label>
                                                                    <input class="form-control" type="text"
                                                                        name="dancestylename" placeholder="Dance Style Name" value="{{$dancestyle->dancestyleName}}"
                                                                        required="">
                                                                        <input type="hidden" name="dancestyleid" value="{{$dancestyle->dancestyleId}}">
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
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#dancestyleedit{{$dancestyle->dancestyleId}}">Edit</a>
                                </td>
                                @endcan
                                <!-- edit dance style end -->
                                <!-- status dance style start -->
                                @can('Softdelete Masters')
                                <td>
                                <div class="modal fade" id="statusmodal{{$dancestyle->dancestyleId}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title">Dance style Status</h5>
                                          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{route('master-dancestyle-chnagestatus')}}" method="post">
                                          @csrf
                                          <div class="modal-body">
                                            @if($dancestyle->dancestyleStatus == '1')
                                            <p>{{$dancestyle->dancestyleName}} is in <span class=" ">Active</span> status</p>
                                            <p>Do you realy want to <span class=" ">De-active</span> Dance Style </p>
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="dancestyleid" value="{{$dancestyle->dancestyleId}}">
                                            @else
                                            <p>{{$dancestyle->dancestyleName}} is in <span class=" ">De-active</span> status</p>
                                            <p>Do you realy want to <span class=" ">Active</span> Dance Style </p>
                                            <input type="hidden" name="status" value="1">
                                            <input type="hidden" name="dancestyleid" value="{{$dancestyle->dancestyleId}}">
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
                                    @if ($dancestyle->dancestyleStatus == '1')
                                    <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#statusmodal{{$dancestyle->dancestyleId}}">Active</a>
                                    @else
                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#statusmodal{{$dancestyle->dancestyleId}}">deactivate</a>
                                    @endif
                                </td>
                                @endcan
                                <!-- status dance style end -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection