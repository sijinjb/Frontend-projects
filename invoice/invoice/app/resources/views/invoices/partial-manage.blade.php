<div class="right-panel shadow rounded" style="display:none;" id="invoice-manage-wrapper-{{$invoice->uuid}}">
    <h3>Manage Invoice</h3>
    <div class="manage-invoice-container mb-3">
        <p><b>Assigned To:</b> {{ $invoice->manager_name ?? 'None' }}</p>
        <p><b>Last Updated By:</b> {{$invoice->updated_name}}</p>
        @if(isset($managers_partial))
        {!! $managers_partial !!}
        @endif
        <div class="form-group">
            <label for="">Status</label>
            <select class="form-control manage-status">
                <option value="Collected" {{ $invoice->status == "Collected" ? "selected":"" }}>Collected</option>
                <option value="Pending" {{ $invoice->status == "Pending" ? "selected":"" }}>Pending</option>
                <option value="Discarded" {{ $invoice->status == "Discarded" ? "selected":"" }}>Discarded</option>
                <option value="Rejected" {{ $invoice->status == "Rejected" ? "selected":"" }}>Rejected</option>
            </select>
        </div>
    </div>
    <hr />
    <div class="d-flex justify-content-between mt-4">
        @if($user->id == $invoice->created_by)
            <a href="#" class="btn btn-outline-primary" data-container="invoice-manage-wrapper-{{$invoice->uuid}}"><i class="fas fa-pen-square"></i> Edit</a>
        @endif
        <button type="button" class="btn btn-primary" data-container="invoice-manage-wrapper-{{$invoice->uuid}}"><i class="fas fa-pen-nib"></i> Update</button>
        <button class="btn btn-danger close-invoice-actions" data-toggle="invoice-manage-wrapper-{{$invoice->uuid}}"><i class="far fa-times-circle"></i> Close</button>
    </div>
</div>