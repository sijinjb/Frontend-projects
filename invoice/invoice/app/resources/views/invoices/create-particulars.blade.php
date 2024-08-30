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
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Invoice - {{ $invoice->uuid }} : Add Items / Services</h6>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-12">
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


                    <div class="col-12">
                        <form action="{{ route('app.invoice.add.particulars', ['uuid' => $invoice->uuid]) }}" method="post">
                            @csrf

                            <div id="field_set">
                                <div class="row border-bottom mb-4" id="item-1">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="inv-p-product-1">Select Product</label>
                                            <select name="product[]" id="inv-p-product-1" class="form-control selectable">
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="inv-p-quantity-1">Quantity</label>
                                            <input type="number" id="inv-p-quantity-1" name="quantity[]" step="0.01" onblur="calculateTotal(this)" class="form-control" value="{{ old('price') }}" onchange="validateDecimalPlaces(this)" required>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <p>Total ( Price:<i class="fas fa-rupee-sign"></i> <span id="inv-p-price-1">0</span> ):: <i class="fas fa-rupee-sign"></i> <span id="inv-p-total-1">0</span></p>
                                            <p>GST(<span id="inv-p-gst-percent-1">0</span>%) :: <i class="fas fa-rupee-sign"></i> <span id="inv-p-gst-1">0</span></p>
                                            <p>Net Amount :: <i class="fas fa-rupee-sign"></i> <span id="inv-p-net-1">0</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" id="add-new-item" class="btn btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="far fa-plus-square"></i>
                                    </span>
                                    <span class="text">Add Item</span>
                                </button>

                                <button type="submit" class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-file-invoice"></i>
                                    </span>
                                    <span class="text">Create</span>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="item">
    <div class="col-4">
        <div class="form-group">
            <label for="inv-p-product">Select Product</label>
            <select name="product[]" id="inv-p-product" class="form-control">
                <option value="">Select Product</option>
                @foreach($products as $product)
                <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-2">
        <div class="form-group">
            <label for="inv-p-quantity-1">Quantity</label>
            <input type="number" id="inv-p-quantity" name="quantity[]" step="0.01" onblur="calculateTotal(this)" class="form-control" value="{{ old('price') }}" onchange="validateDecimalPlaces(this)" required>
        </div>
    </div>

    <div class="col-4">
        <div class="form-group">
            <p>Total (Price:<i class="fas fa-rupee-sign"></i> <span id="inv-p-price">0</span> ) : <i class="fas fa-rupee-sign"></i> <span id="inv-p-total">0</span></p>
            <p>GST(<span id="inv-p-gst-percent">0</span>%) : <i class="fas fa-rupee-sign"></i> <span id="inv-p-gst">0</span></p>
            <p>Net Amount: <i class="fas fa-rupee-sign"></i> <span id="inv-p-net">0</span></p>
        </div>
    </div>

    <div class="col-2 d-none">
        <br>
        <button type="button" class="btn btn-outline-danger remove-item" title="remove" data-item="#item">
            <i class="far fa-trash-alt"></i>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var products = JSON.parse('{!! str_replace( "rn" , "" , str_replace( "\\" , "", json_encode($products))) !!}');
    var itemNo = 1;
    var selectedProducts = {};

    $(document).ready(() => {
        $('#inv-p-product-1').select2();
    });

    function validateDecimalPlaces(input) {
        var value = parseFloat(input.value);
        if (!isNaN(value)) {
            input.value = value.toFixed(2);
        }
    }

    function findProduct(productId, rowNo) {
        let product = {};
        if(checkProductSelected(productId , rowNo)){
            alert('Product already selcted');
            return false;
        };

        products.forEach((item, _) => {
            if (item.id == productId) {
                product = item;
                selectedProducts[rowNo] = productId;
                return false;
            }
        });

        return product;
    }

    function checkProductSelected(productId , rowNo) {
        for (const key in selectedProducts) {
            if (Object.hasOwnProperty.call(selectedProducts, key) && selectedProducts[key] == productId && rowNo != key) {
                return true;
            }
        }
        return false;
    }

    function calculateTotal(element) {
        const rowId = $(element).closest('.row').attr('id');
        const rowNo = +(rowId.split('-'))[1];
        const productId = `inv-p-product-${rowNo}`;
        const priceTotal = `#inv-p-total-${rowNo}`;
        const gstPercent = `#inv-p-gst-percent-${rowNo}`;
        const gstTotal = `#inv-p-gst-${rowNo}`;
        const netTotal = `#inv-p-net-${rowNo}`;
        const price = `#inv-p-price-${itemNo}`;

        const product = findProduct($(`#${productId}`).val(), rowNo);
        if(product === false){
            $(element).val('');
            $(`#${productId}`).val('');
            $(`#${productId}`).change();
            return false;
        }
        console.log
        const totalPrice = (product.price || 0) * (+$(element).val());
        const gstAmount = (product.gst_percent / 100) * totalPrice;
        const netAmount = totalPrice + gstAmount;

        $(price).text(product.price || 0);
        $(priceTotal).text(totalPrice.toFixed(2));
        $(gstPercent).text(product.gst_percent || 0);
        $(gstTotal).text(gstAmount.toFixed(2));
        $(netTotal).text(netAmount.toFixed(2));
    }

    $('#add-new-item').on('click', () => {
        itemNo++;
        const rowId = `item-${itemNo}`;
        const productId = `inv-p-product-${itemNo}`;
        const qtyId = `inv-p-quantity-${itemNo}`;
        const priceTotal = `inv-p-total-${itemNo}`;
        const gstPercent = `inv-p-gst-percent-${itemNo}`;
        const gstTotal = `inv-p-gst-${itemNo}`;
        const netTotal = `inv-p-net-${itemNo}`;
        const price = `inv-p-price-${itemNo}`;
        const row = $('#item').clone();

        $(row).removeAttr('id');
        $(row).removeClass('d-none');
        $('#field_set').append(row);

        $(row).attr('id', rowId);
        $(row).find('#inv-p-product').attr('id', productId);
        $(row).find('#inv-p-quantity').attr('id', qtyId);
        $(row).find('#inv-p-total').attr('id', priceTotal);
        $(row).find('#inv-p-gst-percent').attr('id', gstPercent);
        $(row).find('#inv-p-gst').attr('id', gstTotal);
        $(row).find('#inv-p-net').attr('id', netTotal);
        $(row).find('#inv-p-price').attr('id', price);

        $(row).find(`#${productId}`).siblings('label').attr('for', productId);
        $(row).find(`#${qtyId}`).siblings('label').attr('for', qtyId);
        $(row).find(`#${qtyId}`).data('item', itemNo);
        $(row).find('.remove-item').data('item', `#${rowId}`);
        $(row).find('.remove-item').closest('.col-2').removeClass('d-none');
        $(`#${productId}`).select2();
    });

    $(document).on('click', '.remove-item', (e) => {
        const rowId = $(e.target).data('item');
        $(rowId).remove();
    });
</script>
@endsection