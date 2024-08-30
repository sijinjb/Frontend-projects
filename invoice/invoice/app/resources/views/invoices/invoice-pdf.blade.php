<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->uuid }}</title>
    <style>
        p {
            margin-bottom: 0.2rem;
        }

        table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #e3e6f0;
            text-align: inherit;
            text-align: -webkit-match-parent;
        }

        table td {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #e3e6f0;
        }

        table tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05);
        }
    </style>
</head>

<body>
    <div style="max-width: 720px;padding-left: 1.5rem;padding-right: 1.5rem;">
        <div style="margin-bottom: 1.5rem!important;max-width: 100%;">
            <h2 style="text-align: center;">Invoice</h2>
        </div>
        <div style="display:inline-block;max-width: 49%;">
            <p><b>Invoice Id:</b> {{ $invoice->uuid }}</p>
            <p><b>Company:</b> {{ ucwords($userDetails->company_name) }}</p>
            <p><b>Billing Address:</b><br> {{ $userDetails->billing_address }}</p>
        </div>
        <div style="display:inline-block;max-width: 49%;">
            <p><b>GST NO:</b> {{ $userDetails->gstno }}</p>
            <p><b>Contact</b> {{ $userDetails->phone_number }}</p>
            <p><b>Due On:</b> {{ date('d-m-Y' , strtotime($invoice->due_date)) }}</p>
            <p><b>Generated:</b> {{ date('d-m-Y h:i a') }}</p>
        </div>
        <div style="margin-top: 1.5rem!important;max-width: 100%;">
            <table style="width: 100%;margin-bottom: 1rem;color: #858796;border-collapse: collapse;" class="table table-striped">
                <thead>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>GST %</th>
                    <th>GST Amount</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td> {{$item['product_name']}}</td>
                        <td> {{$item['price']}}</td>
                        <td> {{$item['quantity']}}</td>
                        <td> {{$item['amount']}}</td>
                        <td> {{$item['gst_percent']}}</td>
                        <td> {{$item['gst']}}</td>
                        <td style="font-weight: bold;"> {{$item['total']}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" style="font-weight: bold;">Net Total</td>
                        <td>{{$invoice->total_amount}}</td>
                        <td></td>
                        <td>{{$invoice->total_gst}}</td>
                        <td style="font-weight: bolder;">{{$invoice->payable}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>