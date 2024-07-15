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
                <h3>Master Membership</h3>
            </div>
            <!-- Create Membership start -->
            @can('Create Masters')
                <div class="col-sm-6">
                    <div class="modal fade" id="insertmodel" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Membership Create</h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('master-membership-create')}}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-form-label pt-0" for="">Membership Name(*)</label>
                                                    <input class="form-control" type="text" name="membershipname"
                                                        placeholder="Membership Name" required="">
                                                </div>
												<div class="form-group">
													<label class="col-form-label pt-0" for="membershipBenefits">Membership Benefits (*)</label>
													<textarea class="form-control" name="membershipbenefits" id="membershipbenefits" placeholder="Membership Benefits" required=""></textarea>
												</div>                                                
                                                <div class="form-group">
                                                    <label for="discount_amount">Discount Percentage</label>
                                                    <input type="number" name="membershipdiscountamount" class="form-control"
                                                        value="" required>
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
                            data-bs-target="#insertmodel"><i class="fa fa-plus-circle"></i> Create Membership</button>
                    </div>
                </div>
            @endcan
            <!-- Create Membership end -->
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
                            <th>Membership Name</th>
                            <th>Benefits</th>
                            <th>Discount Percentage</th>
                            <th>Added By</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memberships as $membership)
                        <tr>
                            <td>{{$membership->membershipName}}</td>
                            <td>{{$membership->benefits}}</td>
                            <td>{{$membership->membershipDiscountAmount}}</td>
                            <td>{{$membership->userid->name}}</td>
                            <!-- edit Membership start -->
                                @can('Edit Masters')
                                <td>
                                    <div class="modal fade" id="membershipedit{{$membership->membershipId}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalCenter" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Membership Edit</h5>
                                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
													<form action="{{url('master-membership-update')}}" method="POST"
														enctype="multipart/form-data">
														@csrf
														<div class="modal-body">
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label class="col-form-label pt-0" for="">Membership Name(*)</label>
																		<input class="form-control" type="text" name="membershipname"
																		placeholder="Membership Name" value="{{$membership->membershipName}}" required="">
																	</div>
																	<div class="form-group">
																		<label class="col-form-label pt-0" for="membershipBenefits">Membership Benefits (*)</label>
																		<textarea class="form-control" name="membershipbenefits" id="membershipbenefits" placeholder="Membership Benefits" required="">{{$membership->benefits}}</textarea>
																	</div>
																	<div class="form-group">
																		<label for="discount_amount">Discount Percentage</label>
																		<input type="number" name="membershipdiscountamount" class="form-control"
																		value="{{$membership->membershipDiscountAmount}}" required>
																	</div>
																	
																	<div class="form-group">
																		<input type="hidden" name="membershipId" value="{{$membership->membershipId}}">
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
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#membershipedit{{$membership->membershipId}}">Edit</a>
                                </td>
                                @endcan
                                <!-- edit memberships end -->
                                 <!-- status memberships start -->
                                @can('Softdelete Masters')
                                <td>
                                <div class="modal fade" id="statusmodal{{$membership->membershipId}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title">membership Status</h5>
                                          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{route('master-membership-chnagestatus')}}" method="post">
                                          @csrf
                                          <div class="modal-body">
                                            @if($membership->membershipStatus == '1')
                                            <p>{{$membership->membershipName}} is in <span class=" ">Active</span> status</p>
                                            <p>Do you realy want to <span class=" ">De-active</span> membership </p>
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="membershipid" value="{{$membership->membershipId}}">
                                            @else
                                            <p>{{$membership->membershipName}} is in <span class=" ">De-active</span> status</p>
                                            <p>Do you realy want to <span class=" ">Active</span> membership </p>
                                            <input type="hidden" name="status" value="1">
                                            <input type="hidden" name="membershipid" value="{{$membership->membershipId}}">
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
                                    @if ($membership->membershipStatus == '1')
                                    <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#statusmodal{{$membership->membershipId}}">Active</a>
                                    @else
                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#statusmodal{{$membership->membershipId}}">deactivate</a>
                                    @endif
                                </td>
                                @endcan
                                <!-- status memberships end -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection