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
                <th scope="col">Type</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col">GST %</th>
                <th scope="col">Added By</th>
                <th scope="col">Last Modified</th>
                <th scope="col">Actions</th>
                <!-- <tr>
                </tr> -->
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <th scope="row"><a href="{{ route('app.product.edit', ['uuid' => $product->uuid]) }}" title="Edit product"> {{ $product->uuid }} </a></th>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->gst_percent }} %</td>
                    <td>{{ $product->user_name }}</td>
                    <td>{{ date('d-m-Y h:i a' , strtotime($product->updated_at. ' +5 hours 30 minutes')) }}</td>
                    <td>
                        <a href="{{ route('app.product.edit', ['uuid' => $product->uuid]) }}" title="Edit product"><i class="far fa-edit"></i></a>
                        <a href="{{ route('app.product.delete', ['uuid' => $product->uuid]) }}" onclick="return confirm('Are you sure you want to delete this product?')" title="Delete product"><i class="far fa-trash-alt"></i></a>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection