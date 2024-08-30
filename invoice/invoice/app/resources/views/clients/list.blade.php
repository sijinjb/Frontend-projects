@extends('layouts.app')

@section('style')
    <style>
        th {
            white-space: nowrap;
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
        <table class="table table-striped">
            <thead>
                <th scope="col">UUID</th>
                <th scope="col">Name</th>
                <th scope="col">Mobile</th>
                <th scope="col">Client</th>
                <th scope="col">GST</th>
                <th scope="col">PAN</th>
                <th scope="col">Billing Address</th>
                <th scope="col">State</th>
                <th scope="col">Added By</th>
                <th scope="col">Last Modified</th>
                <th scope="col">Actions</th>
                <!-- <tr>
                </tr> -->
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <th scope="row"><a href="{{ route('app.client.edit', ['uuid' => $client->uuid]) }}" title="Edit Client"> {{ $client->uuid }} </a></th>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->mobile }}</td>
                    <td>{{ $client->type }}</td>
                    <td>{{ $client->gstno }}</td>
                    <td>{{ $client->pan }}</td>
                    <td>{{ $client->billing_address }}</td>
                    <td>{{ $client->billing_state }}</td>
                    <td>{{ $client->user_name }}</td>
                    <td>{{ date('d-m-Y h:i a' , strtotime($client->updated_at. ' +5 hours 30 minutes')) }}</td>
                    <td>
                        <a href="{{ route('app.client.edit', ['uuid' => $client->uuid]) }}" title="Edit Client"><i class="far fa-edit"></i></a>
                        <a href="{{ route('app.client.delete', ['uuid' => $client->uuid]) }}" onclick="return confirm('Are you sure you want to delete this client?')" title="Delete Client"><i class="far fa-trash-alt"></i></a>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection