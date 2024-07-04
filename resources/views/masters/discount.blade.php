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
                <h3>Master Discounts</h3>
            </div>
            <!-- Create Discount start -->
            @can('Create Masters')
                <div class="col-sm-6">
                    <div class="modal fade" id="insertmodel" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Discount Create</h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{route('master-discount-create')}}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-form-label pt-0" for="">Discount Name
                                                        (*)</label>
                                                    <input class="form-control" type="text" name="discountname"
                                                        placeholder="Discount Name" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label" for="">Discount Type
                                                        (*)</label>
                                                    <select name="discounttypes" id="" class="form-control">
                                                        <option value="single">Single</option>
                                                        <option value="couple">Couple</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="min_sessions">Min Sessions</label>
                                                    <input type="number" name="min_sessions" class="form-control" value=""
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="max_sessions">Max Sessions</label>
                                                    <input type="number" name="max_sessions" class="form-control" value=""
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="discount_amount">Discount Amount</label>
                                                    <input type="text" name="discount_amount" class="form-control"
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
                            data-bs-target="#insertmodel"><i class="fa fa-plus-circle"></i> Create Discounts</button>
                    </div>
                </div>
            @endcan
            <!-- Create Discount end -->
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
                            <th>Discount Name</th>
                            <th>Discount Type</th>
                            <th>Session-range</th>
                            <th>Discount Amount</th>
                            <th>Added By</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($discounts as $discount)
                        <tr>
                            <td>{{$discount->discountName}}</td>
                            <td>{{$discount->discountType}}</td>
                            <td>{{$discount->minSessions}}-{{$discount->maxSessions}}</td>
                            <td>{{$discount->discountAmount}}</td>
                            <td>{{$discount->userid->name}}</td>
                            <!-- edit Discounts start -->
                                @can('Edit Masters')
                                <td>
                                    <div class="modal fade" id="discountedit{{$discount->discountId}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalCenter" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Discount Edit</h5>
                                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{url('master-discount-update')}}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-form-label pt-0" for="">Discount Name
                                                                    (*)</label>
                                                                    <input class="form-control" type="text" name="discountname"
                                                                    placeholder="Discount Name" value="{{$discount->discountName}}" required="">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-form-label" for="">Discount Type
                                                                    (*)</label>
                                                                    <select name="discounttypes" id="" class="form-control">
                                                                        <option value="single" {{ $discount->discountType == 'single' ? 'selected' : '' }}>Single</option>
                                                                        <option value="couple" {{ $discount->discountType == 'couple' ? 'selected' : '' }}>Couple</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="min_sessions">Min Sessions</label>
                                                                    <input type="number" name="min_sessions" class="form-control" value="{{$discount->minSessions}}"
                                                                    required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="max_sessions">Max Sessions</label>
                                                                    <input type="number" name="max_sessions" class="form-control" value="{{$discount->maxSessions}}"
                                                                    required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="discount_amount">Discount Amount</label>
                                                                    <input type="text" name="discount_amount" class="form-control"
                                                                    value="{{$discount->discountAmount}}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="hidden" name="discountId" value="{{$discount->discountId}}">
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
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#discountedit{{$discount->discountId}}">Edit</a>
                                </td>
                                @endcan
                                <!-- edit Discounts end -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection