@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Client : {{ $client->name ?? old('name') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-7">
                        <form action="{{ route('app.client.edit' , ['uuid' => $client->uuid]) }}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="c-name">Name</label>
                                <input type="text" name="name" id="c-name" class="form-control" value="{{ $client->name ?? old('name') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="c-type">Type</label>
                                <select name="type" id="c-type" class="form-control" required>
                                    <option value="">Select client type</option>
                                    <option value="Customer" {{ (old('type') == 'Customer' || (isset($client->type) && $client->type == 'Customer')) ? 'selected':'' }}>Customer</option>
                                    <option value="Supplier" {{ (old('type') == 'Supplier' || (isset($client->type) && $client->type == 'Customer'))? 'selected':'' }}>Supplier</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="c-mobile">Mobile</label>
                                <input type="tel" name="mobile" value="{{ $client->mobile ?? old('mobile') }}" id="c-mobile" class="form-control" minlength="10" maxlength="10" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="c-gstno">GST No</label>
                                <input type="text" name="gstno" value="{{ $client->gstno ?? old('gstno') }}" id="c-gstno" class="form-control" minlength="15" maxlength="15" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="c-pan">Pan</label>
                                <input type="text" name="pan" value="{{ $client->pan ?? old('pan') }}" id="c-pan" class="form-control" minlength="10" maxlength="10" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="c-billing-address">Billing Address</label>
                                <textarea name="billing_address" id="c-billing-address" cols="30" rows="3" class="form-control" required>{{ $client->billing_address ?? old('billing_address') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="c-billing-address">Billing State</label>
                                <input type="text" value="{{ $client->billing_state ?? old('billing_state') }}" name="billing_state" id="c-billing-state" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-address-book"></i>
                                </span>
                                <input type="hidden" name="uuid" value="{{ $client->uuid ?? old('uuid') }}">
                                <span class="text">Update Client</span>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection