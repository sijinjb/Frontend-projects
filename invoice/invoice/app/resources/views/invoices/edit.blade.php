@extends('layouts.app')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Generate Invoice</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-7">
                        <form action="{{ route('app.invoice.add') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="inv-gen-date">Generated Date</label>
                                <input type="date" name="generated_date" id="inv-gen-date" class="form-control" value="{{ date('Y-m-d') }}" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="inv-client">Client</label>
                                <select name="client" id="inv-client" class="form-control" required>
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inv-type">Type</label>
                                <select name="type" id="inv-type" class="form-control">
                                    <option value="">Select Invoice Type</option>
                                    <option value="Delivery">Delivery</option>
                                    <option value="Payment">Payment</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inv-due-date">Due Date</label>
                                <input type="date" name="due_date" id="inv-due-date" class="form-control" title="By default 7 days from today" value="{{ date('Y-m-d' , strtotime('+7 days')) }}" required>
                            </div>

                            <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                <i class="fas fa-file-invoice"></i>
                                </span>
                                <span class="text">Continue</span>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(() => {
        $('#inv-client').select2();
    });
</script>
@endsection