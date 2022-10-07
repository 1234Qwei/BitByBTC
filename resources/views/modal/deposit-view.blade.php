<form action="{{ route('exchange-update') }}" method="POST" id="depositUpdateForm" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="dd_exchange_id" value="{{ $exchange->id }}" />
    <input type="hidden" name="exchange_type" id="exchange_type" value="{{ $exchange->exchange_type }}" />
    <div class="modal-body">
        <dl class="row">
            <dt class="col-sm-4">Exchange type</dt>
            <dd class="col-sm-8">{{ $exchange->exchange_type == '1' ? 'Deposit' : 'Swap' }}</dd>
            <dt class="col-sm-4">Requested Date</dt>
            <dd class="col-sm-8">{{ $exchange->created_at }}</dd>
            @if ($exchange->approved_date)
                <dt class="col-sm-4">Approved Date</dt>
                <dd class="col-sm-8">{{ $exchange->approved_date }}</dd>
            @endif
            <dt class="col-sm-4">Deposit Currency</dt>
            <dd class="col-sm-8"> {{ $exchange->deposit->coin }}</dd>
            <dt class="col-sm-4">Deposit @if ($exchange->deposit->symbol == 'INR')
                    Amount
                @else
                    Coin
                @endif
            </dt>
            <dd class="col-sm-8"> {{ $exchange->deposit_coin }} {{ $exchange->deposit->symbol }}</dd>
            @if (!$exchange->is_referral && !$exchange->is_stacking)
                @if (Auth::user()->id !== 1 && !is_null($exchange->bank_id) && is_null($exchange->bank_proof))
                    <dt class="col-sm-4">Deposit Proof</dt>
                    <dd class="col-sm-8">
                        <input type="file" name="bank_proof" id="bank_proof" required="required"
                            accept=".pdf,.png,.jpg,.jpeg" />
                    </dd>
                @elseif(!is_null($exchange->bank_id) && !is_null($exchange->bank_proof))
                    <dt class="col-sm-4">Deposit Proof</dt>
                    <dd class="col-sm-8">
                        {{ $exchange->bank_proof }} <a href="{{ url('download') . '/' . $exchange->bank_proof }}"><i
                                class="fa fa-download"></i></a>
                    </dd>
                @else
                    <dt class="col-sm-4">Transaction #ID</dt>
                    <dd class="col-sm-8">
                        @if (!is_null($exchange->transaction_id))
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $exchange->transaction_id }}"
                                    name="transaction_id" readonly id="deposit-tranactionhash" />
                                <div class="input-group-append">
                                    <button class="cpy-btn btn btn-outline-secondary" type="button"
                                        data-clipboard-text="{{ $exchange->transaction_id }}"><i
                                            class="fa fa-copy"></i></button>
                                </div>
                            </div>
                        @elseif(is_null($exchange->transaction_id))
                            <div class="form-group">
                                <input type="text" class="form-control" value="{{ $exchange->transaction_id }}"
                                    name="transaction_id" id="deposit-tranactionhash" required="required" />
                            </div>
                        @else
                            <div class="form-group">
                                <label class="form-control">Not yet updated this! </label>
                            </div>
                        @endif
                    </dd>
                @endif
            @endif
            <dt class="col-sm-4">Status</dt>

            <dd class="col-sm-8">
                @if (is_null($exchange->transaction_id) &&
                    is_null($exchange->bank_proof) &&
                    !$exchange->is_referral &&
                    !$exchange->is_stacking)
                    <span class="badge bg-warning">Please update the transaction hash / proof is important!</span>
                @elseif($exchange->status == 0)
                    <span class="badge bg-primary">Not Verified</span>
                @elseif($exchange->status == 1)
                    <span class="badge bg-success">Approved</span>
                @else
                    <span class="badge bg-danger">Rejected</span>
                @endif
            </dd>

            @if ($exchange->approver_comment)
                <dt class="col-sm-4">Approver Comments</dt>
                <dd class="col-sm-8" id="dd_approver_comment"> {{ $exchange->approver_comment }}</dd>
            @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger deposit_button" data-dismiss="modal">Close!</button>
        @if (is_null($exchange->transaction_id) &&
            is_null($exchange->bank_proof) &&
            !$exchange->is_referral &&
            !$exchange->is_stacking)
            <button type="submit" class="btn btn-primary deposit_button" id="deposit_submit">Update!</button>
        @endif
    </div>
</form>
