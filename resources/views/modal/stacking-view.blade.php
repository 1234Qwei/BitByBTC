<form action="{{ route('stacking-update') }}" method="POST" id="stackingUpdateForm" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="stacking_id" value="{{ $stacking->id }}" />
    <div class="modal-body">
        <dl class="row"> 
            <dt class="col-sm-4">Requested Date</dt>
            <dd class="col-sm-8">{{ $stacking->created_at }}</dd>
            @if ($stacking->approved_date)
                <dt class="col-sm-4">Approved Date</dt>
                <dd class="col-sm-8">{{ $stacking->approved_date }}</dd>
            @endif
            <dt class="col-sm-4">Stacking Currency</dt>
            <dd class="col-sm-8"> {{ $stacking->package->asset->coin }}</dd>
            <dt class="col-sm-4">Stacking Coin</dt>
            <dd class="col-sm-8"> {{ $stacking->package->coin }} {{ $stacking->package->asset->symbol }}</dd>
            <dt class="col-sm-4">Stacking Term</dt>
            <dd class="col-sm-8"> {{ $stacking->term->duration . ' Month' }}
                ({{ $stacking->term->interest }}%)</dd>
            <dt class="col-sm-4">Transaction #ID</dt>
            <dd class="col-sm-8">
                @if (!is_null($stacking->transaction_id))
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $stacking->transaction_id }}"
                            name="transaction_id" readonly id="stacking-tranactionhash" />
                        <div class="input-group-append">
                            <button class="cpy-btn btn btn-outline-secondary" type="button"
                                data-clipboard-text="{{ $stacking->transaction_id }}"><i
                                    class="fa fa-copy"></i></button>
                        </div>
                    </div>
                @elseif(Auth::user()->id !== 1 && $stacking->status == 0)
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{ $stacking->transaction_id }}"
                            name="transaction_id" id="stacking-tranactionhash" required="required" />
                    </div>
                @else
                    <div class="form-group">
                        <label class="form-control">Not yet updated this! </label>
                    </div>
                @endif
            </dd>
            <dt class="col-sm-4">Status</dt>
            @if (Auth::user()->id === 1)
                <dd class="col-sm-8">
                    <div class="col-4 form-group clearfix">
                        <div class="icheck-success d-inline">
                            <input type="radio" name="status" id="dd_approve" value="1" required
                                @if ($stacking->status == 1) checked @endif />
                            <label for="dd_approve">Approve</label>
                        </div>
                    </div>
                    <div class="col-4 form-group clearfix">
                        <div class="icheck-success d-inline">
                            <input type="radio" name="status" id="dd_reject" value="2" required
                                @if ($stacking->status == 2) checked @endif />
                            <label for="dd_reject">Reject</label>
                        </div>
                    </div>
                </dd>
            @else
                <dd class="col-sm-8">
                    @if (is_null($stacking->transaction_id) && $stacking->status == 0)
                        <span class="badge bg-warning">Please update the transaction hash is important!</span>
                    @elseif($stacking->status == 0)
                        <span class="badge bg-primary">Not Verified</span>
                    @elseif($stacking->status == 1)
                        <span class="badge bg-success">Approved</span>
                    @elseif($stacking->status == 2)
                        <span class="badge bg-danger">Rejected</span>
                    @elseif($stacking->status == 3)
                        <span class="badge bg-info">Closed</span>
                    @endif
                </dd>
            @endif
            @if (Auth::user()->id === 1)
                <dt class="col-sm-4">Approver Comments</dt>
                <dd class="col-sm-8">
                    <textarea class="form-control" name="approver_comment"
                        id="dd_approver_comment">{{ $stacking->approver_comment ?? '' }}</textarea>
                </dd>
        </dl>
    @else
        @if ($stacking->approver_comment)
            <dt class="col-sm-4">Approver Comments</dt>
            <dd class="col-sm-8" id="dd_approver_comment"> {{ $stacking->approver_comment }}</dd>
        @endif
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger stacking_button" data-dismiss="modal">Close!</button>
        @if (Auth::user()->id === 1 || (is_null($stacking->transaction_id) && is_null($stacking->bank_proof) && !$stacking->is_referral && $stacking->status == 0))
            <button type="submit" class="btn btn-primary stacking_button" id="stacking_submit">Update!</button>
        @endif
    </div>
</form>
