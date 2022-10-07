@include('layout.home-header')
<div class="header-out-1">
    <div class="container">
        <div class="package-2">
            <div class="package-title"> Withdraw admin</div>
            <div class="e-ou-space">
                <div class="p-5">
                    <form id="withdrawForm" method="post" action="{{ route('withdraw') }}" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $isEdit ? $data['withdraw']->id : 0 }}">
                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">Withdraw Request &nbsp;<span
                                    class="text-danger"><small><strong>Before withdrawal Make sure your
                                            {{ $data['coin']->symbol }} Address is correct.</strong></small> </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label> Available ({{ $data['coin']->symbol }}) </label>
                                <input class="form-control" name="available_amount" type="text" autocomplete="off"
                                    value="{{ $data['balanceAmount'] ?? 0 }}" disabled id="available_amount" />
                            </div>
                            <div class="form-group">
                                <label> Withdraw ({{ $data['coin']->symbol }}) <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" maxlength="15" name="amount" id="amount" type="text"
                                    autocomplete="off" @if ($isEdit) disabled @endif
                                    value="{{ $isEdit ? $data['withdraw']->amount : '' }}" />
                            </div>
                            @if ($data['coin']->coin_id === 'indian_rupee')
                                <div class="form-group">
                                    <label> Bank accounts<span class="text-danger">*</span></label>
                                    @include('modal.inr-deposit', [
                                        'banks' => $data['banks'],
                                        'bank_id' => $isEdit ? $data['withdraw']->bank_id : null,
                                    ])
                                </div>
                            @else
                                <div class="form-group">
                                    <label> Wallet Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="walletaddress"
                                            id="walletaddress" type="text" autocomplete="off"
                                            value="{{ $isEdit ? $data['withdraw']->wallet_address : '' }}"
                                            @if ($isEdit) readonly @endif />
                                        <span class="input-group-btn">
                                            <button class="cpy-btn btn btn-default" type="button"
                                                data-clipboard-target="#walletaddress">
                                                Copy
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @if ($isEdit && !is_null($data['withdraw']->bank_id) && is_null($data['withdraw']->bank_proof))
                                <div class="form-group">
                                    <label> Withdraw proof <span class="text-danger">*</span></label>
                                    <input type="file" name="bank_proof" id="bank_proof"
                                        accept="image/*, application/pdf" />
                                </div>
                            @elseif($isEdit && !is_null($data['withdraw']->bank_id) && !is_null($data['withdraw']->bank_proof))
                                <div class="form-group">
                                    <label>
                                        Withdraw proof : {{ $data['withdraw']->bank_proof }} <a
                                            href="{{ url('download') . '/' . $data['withdraw']->bank_proof }}"><i
                                                class="fa fa-download"></i></a>
                                    </label>
                                </div>
                            @else
                                <div class="form-group">
                                    <label> Transaction Hash <span class="text-danger">*</span></label>
                                    <input class="form-control" name="transaction_hash" id="transaction_hash"
                                        type="text" autocomplete="off"
                                        value="{{ $isEdit ? $data['withdraw']->transaction_hash : '' }}" />
                                </div>
                            @endif
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" cols="28" id="remarks" maxlength="400" name="remarks" rows="4"
                                    @if ($isEdit) disabled @endif>{{ $isEdit ? $data['withdraw']->remarks : '' }}</textarea>
                            </div>
                            @if (!$isEdit)
                                <div class="form-group hide" id="js-otp-div">
                                    <label> OTP <span class="text-danger">*</span></label>
                                    <input class="form-control" maxlength="200" name="otp" id="js-otp"
                                        type="text" autocomplete="off" placeholder="Enter your OTP" />
                                    <span
                                        style="float: left;font-size: 12px;font-weight: bold;padding: 5px;margin-bottom: 10px;color: red;">Note:
                                        we sent OTP into your register email address, please check.</span>
                                </div>
                            @endif
                            @if (Auth::user()->id === 1 && $isEdit)
                                <div class="form-group">
                                    <label>Staus</label>
                                    <div class="ml-5 icheck-success d-inline">
                                        <input type="radio" name="status" id="pending" value="1"
                                            @if ($data['withdraw']->status == 1) checked @endif />
                                        <label for="pending">
                                            Payout Pending
                                        </label>
                                    </div>
                                    <div class="ml-5 icheck-success d-inline">
                                        <input type="radio" name="status" id="approved" value="2"
                                            @if ($data['withdraw']->status == 2) checked @endif />
                                        <label for="approved">
                                            Approved
                                        </label>
                                    </div>
                                    <div class="ml-5 icheck-success d-inline">
                                        <input type="radio" name="status" id="rejected" value="3"
                                            @if ($data['withdraw']->status == 3) checked @endif />
                                        <label for="rejected">
                                            Rejected
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" name="is_withdraw_fee_checked"
                                        id="is_withdraw_fee_checked" value="1" required="required"
                                        @if ($isEdit && $data['withdraw']->is_withdraw_fee_checked == 1) checked @endif />
                                    <label for="is_withdraw_fee_checked">
                                        Withdraw fee per transaction
                                        {{ $data['coin']->withdraw_fee . ' ' . $data['coin']->symbol }}
                                    </label>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary js-progress"
                        @if ($data['withdraw']->status != 1) disabled="disabled" @endif>Submit</button>
                    <a href="{{ url('withdraw-request') }}" class="btn btn-danger js-progress">Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@push('scripts')
@endpush
@include('layout.home-footer')
