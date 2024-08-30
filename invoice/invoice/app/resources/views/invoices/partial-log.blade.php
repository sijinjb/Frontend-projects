@php
$comments = json_decode($invoice->comments);
@endphp


<div class="right-panel shadow rounded" style="display:none;" id="invoice-logs-wrapper-{{$invoice->uuid}}">
    <h3>Invoice Logs</h3>
    <div>
        @foreach($comments as $comment)
        <p><b>Message:</b> {{$comment->message}}</p>
        <p><b>Updated On:</b> {{ date('d-m-Y h:i a' , strtotime($comment->ts)) }}</p>
        <hr />
        @endforeach
    </div>
    <button class="btn btn-danger close-invoice-logs" data-toggle="invoice-logs-wrapper-{{$invoice->uuid}}"><i class="far fa-times-circle"></i> Close</button>
</div>