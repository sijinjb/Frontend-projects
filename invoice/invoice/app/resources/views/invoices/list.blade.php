@extends('layouts.app')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-selection.select2-selection--single {
        height: 40px;
    }

    .select2-selection__rendered {
        margin-top: 4px;
    }

    .select2.select2-container.select2-container--default {
        width: 100% !important;
    }

    th {
        white-space: nowrap;
    }

    .right-panel {
        display: block;
        position: fixed;
        width: 30%;
        height: calc(100vh - 80px);
        top: 80px;
        right: 0;
        z-index: 100;
        background-color: #ffffff;
        white-space: normal;
        padding: 5px;
        overflow-y: scroll;
    }
    .fixed-panel{
        display: block;
        position: fixed;
        height: calc(100vh - 160px);
        top: 80px;
        right: 0;
        width: 70%;
        background-color: #ffffff;
        white-space: normal;
        padding: 5px;
        overflow-y: scroll;
    }
</style>
@endsection

@section('content')
<div class="row">

    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="col-12">
        <div class="card shadow mb-3">
            <div class="card-body d-flex justify-content-between">
                <h3>Manage Invoices</h3>
                <a href="{{route('app.invoice.add')}}" class="btn btn-outline-primary"><i class="fas fa-file-invoice"></i> New Invoice</a>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <th>UUID</th>
                        <th>User</th>
                        <th>Client</th>
                        <th>Invoice Amount (<i class="fas fa-rupee-sign"></i>)</th>
                        <th>Status</th>
                        <th>Assignee</th>
                        <th>Due Date</th>
                        <th>Invoice Type</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </thead>
        
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td><a href="javascript:;" class="load-particulars" data-route="{{ route('app.invoice.particulars' , ['invoice_id' => $invoice->id]) }}">{{$invoice->uuid}}</a></td>
                            <td>{{$invoice->created_name}}</td>
                            <td>{{$invoice->client_name}}</td>
                            <td>
                                <p class="mb-0 pb-0"><b>Amount:</b> {{$invoice->total_amount}}</p>
                                <p class="mb-0 pb-0"><b>GST:</b> {{$invoice->total_gst}}</p>
                                <p class="mb-0 pb-0 "><b class="badge badge-danger">Payable</b> <b>{{$invoice->payable}}</b></p>
                            </td>
                            <td>{{$invoice->status}}</td>
                            <td>{{$invoice->manager_name ?? 'Unassigned'}}</td>
                            <th>{{ date('d-m-Y' , strtotime($invoice->due_date)) }}</th>
                            <th>{{ $invoice->type }}</th>
                            <th>{{ date('d-m-Y h:i a' , strtotime($invoice->updated_at . "+5 hours 30 minutes")) }}</th>
                            <th>
                                <button class="btn btn-outline-primary show-invoice-logs" data-toggle="invoice-logs-wrapper-{{$invoice->uuid}}" title="View Logs">Logs</button>
                                <button class="btn btn-outline-primary show-invoice-actions" data-toggle="invoice-manage-wrapper-{{$invoice->uuid}}" title="Manage Invoice">Manage</button>
                                <a class="btn btn-outline-primary show-invoice-actions" target="_blank" href="{{ route('app.invoice.generate' , ['uuid' => $invoice->uuid]) }}" title="Manage Invoice">View</a>
                                @include('invoices.partial-log')
                                @include('invoices.partial-manage')
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="show-particulars-wrapper"></div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    /* Toggle invoice logs */
    $('.show-invoice-logs').on('click', (e) => {
        const logContainerId = `#${$(e.target).data('toggle')}`;
        $(logContainerId).show(200);
    });
    $('.close-invoice-logs').on('click', (e) => {
        const logContainerId = `#${$(e.target).data('toggle')}`;
        $(logContainerId).hide(200);
    });

    /* Toggle invoice actions */
    $('.show-invoice-actions').on('click', (e) => {
        const logContainerId = `#${$(e.target).data('toggle')}`;
        $(`${logContainerId} .manager-list`).select2();
        $(logContainerId).show(200);
    });
    $('.close-invoice-actions').on('click', (e) => {
        const logContainerId = `#${$(e.target).data('toggle')}`;
        $(logContainerId).hide(200);
    });

    let currentCall = false;
    $('.load-particulars').on('click' , async (e) => {
        if(currentCall){
            alert("Please wait, loading items");
            return false;
        }
        const route = $(e.target).data('route');
        currentCall = true;
        const response = await fetch(route+"?type=html");
        if(response.status > 200){
            alert("Failed to load invoice items");
        } else {
            const data = await response.text();
            $('.show-particulars-wrapper').html(data);
        }
        currentCall = false;
    });
</script>
@endsection