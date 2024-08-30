@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Unauthorized Access</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h1 style="font-size: 100px;color:brown">403</h1>
                        {{$message ?? ''}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection