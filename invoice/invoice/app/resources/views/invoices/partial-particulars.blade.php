<div class="shadow rounded fixed-panel" id="invoice-item-detail">
    <h3>Invoice Items</h3>
    <table class="table table-striped table-hover">
        <thead>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Amount</th>
            <th>GST %</th>
            <th>GST Amount</th>
            <th>Total</th>
            <th>Last Updated</th>
            <th>Logs</th>
        </thead>
        <tbody>
            @foreach($items as $item)
            @php
            $comments = json_decode($item['comments']);
            @endphp

            <tr>
                <td> {{$item['product_name']}}</td>
                <td> {{$item['price']}}</td>
                <td> {{$item['quantity']}}</td>
                <td> {{$item['amount']}}</td>
                <td> {{$item['gst_percent']}}</td>
                <td> {{$item['gst']}}</td>
                <td class="font-weight-bold"> {{$item['total']}}</td>
                <td> {{ date('d-m-Y h:i a' , strtotime($item['updated_at']. "+ 5 hours 30 minutes")) }} BY {{ $item['updated_by_name'] }}</td>
                <td>
                    @foreach($comments as $comment)
                    <p>{{ $comment->message }} - {{ $comment->ts }}</p>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end pr-4">
        <button type="button" class="btn btn-danger close-invoice-item"><i class="far fa-times-circle"></i> Close</button>
    </div>
</div>
<script>
    $(document).on('click', '.close-invoice-item', (e) => {
        $('#invoice-item-detail').hide(250, () => {
            $('#invoice-item-detail').remove();
        });
    });
</script>