@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Add Your Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <form action="{{ route('app.user.details') }}" method="post">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="u-company">Company Name</label>
                                <input type="text" name="company_name" value="{{ $userDetails->company_name ?? '' }}" id="u-company" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="u-address">Address</label>
                                <textarea name="address" id="u-address" class="form-control" cols="30" rows="5" required>{{ $userDetails->address ?? "" }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="u-b-address">Billing Address</label>
                                <textarea name="billing_address" id="u-b-address" cols="30" class="form-control" rows="5" required>{{ $userDetails->billing_address ?? "" }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="u-mobile">Phone Number</label>
                                <input type="tel" name="phone_number" value="{{ $userDetails->phone_number ?? '' }}" id="u-mobile" class="form-control" minlength="10" maxlength="10" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="u-gstno">GST No</label>
                                <input type="text" name="gstno" value="{{ $userDetails->gstno ?? '' }}" id="u-gstno" class="form-control" minlength="15" maxlength="15" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="u-pan">Pan</label>
                                <input type="text" name="pan" value="{{ $userDetails->pan ?? '' }}" id="u-pan" class="form-control" minlength="10" maxlength="10" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="u-b-state">Billing State</label>
                                <input type="text" value="{{ $userDetails->billing_state ?? '' }}" name="billing_state" id="u-b-state" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <span class="text">Update</span>
                            </button>

                        </form>
                    </div>
                    <div class="col-5">
                        @if(isset($error))
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if (session('success'))
                        <div class="alert alert-success">
                            {!! session('success') !!}
                        </div>
                        @endif

                        @if (session('warning'))
                        <div class="alert alert-warning">
                            {!! session('warning') !!}
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="alert alert-danger">
                            {!! session('error') !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection