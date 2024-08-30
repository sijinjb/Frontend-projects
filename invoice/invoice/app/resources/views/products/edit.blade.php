@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Product : {{ $product->name }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-7">
                        <form action="{{ route('app.product.edit' , ['uuid' => $product->uuid]) }}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="p-name">Name</label>
                                <input type="text" name="name" id="p-name" class="form-control" value="{{ $product->name ?? old('name') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="p-type">Type</label>
                                <select name="type" id="p-type" class="form-control" required>
                                    <option value="">Select Product type</option>
                                    <option value="Item" {{ old('type') == 'Item' || $product->type == 'Item' ? 'selected':'' }}>Item</option>
                                    <option value="Service" {{ old('type') == 'Service' || $product->type == 'Service' ? 'selected':'' }}>Service</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="p-description">Desription</label>
                                <textarea name="description" id="p-description" cols="30" rows="3" class="form-control" required>{{ $product->description ?? old('description') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="p-price">Price</label>
                                <input type="number" id="p-price" name="price" step="0.01" class="form-control" value="{{ $product->price ?? old('price') }}" onchange="validateDecimalPlaces(this)" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="p-gst">GST %</label>
                                <input type="number" id="p-gst" name="gst_percent" class="form-control" step="0.01" value="{{ $product->gst_percent ?? old('gst_percent') }}" onchange="validateDecimalPlaces(this)" min="0" max="100" required>
                            </div>

                            <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-cubes"></i>
                                </span>
                                <span class="text">Update Product</span>
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
<script>
    function validateDecimalPlaces(input) {
        var value = parseFloat(input.value);
        if (!isNaN(value)) {
            input.value = value.toFixed(2);
        }
    }
</script>
@endsection