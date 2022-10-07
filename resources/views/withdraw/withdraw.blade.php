@include('layout.home-header')
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title">Withdraw Request &nbsp;<span
		class="text-danger"><small><strong>Before withdrawal Make sure your
				{{ $data['coin']->symbol }} Address is correct.</strong></small> | <small
			class="text-warning"> INR Deposit and
			Withdrawal is to be processed at 10 AM to 5 PM IST.</small> </span></h3></div>
      <div class="e-ou-space">
        <div class="p-5">
           <form id="withdrawForm">
			<input type="hidden" name="temp_id" id="js-tempid" />
			<input type="hidden" name="coin_id" id="coin_id" value="{{ $data['coin']->id }}" />
			<input type="hidden" name="coin" id="coin" value="{{ $data['coin']->coin_id }}" />
			<input type="hidden" name="id" value="{{ $isEdit ? $data['withdraw']->id : 0 }}">
			<input type="hidden" name="withdraw_min" value="{{ $data['coin']->withdraw_min }}">
			@csrf
            <div class="form-group">
				<label> Available ({{ $data['coin']->symbol }}) </label>
				<input class="form-control" name="available_amount" type="text" autocomplete="off"
					value="{{ $data['balanceAmount'] ?? 0 }}" disabled id="available_amount" />
            </div>
			
            <div class="form-group">
				<label> Withdraw ({{ $data['coin']->symbol }}) <span
						class="text-danger">*</span></label>
				<input class="form-control" min="{{ $data['coin']->swap_min }}"
					max="{{ $data['coin']->swap_max }}" name="amount" id="amount" type="text"
					autocomplete="off" @if ($isEdit) disabled @endif
					value="{{ $isEdit ? $data['withdraw']->amount : '' }}" />
				@if ($isEdit)
					<small>Final withdraw amount excluding withdraw fees:
						<strong>{{ $data['withdraw']->final_amount . ' ' . $data['coin']->symbol }}</strong></small>
				@endif
            </div>			
			
			@if ($data['coin']->coin_id === 'indian_rupee')
				<div class="form-group withdraw">
					<label> Bank accounts<span class="text-danger">*</span></label>
					@if ($data['banks'])
						@include('modal.inr-deposit', [
							'banks' => $data['banks'],
							'bank_id' => $isEdit ? $data['withdraw']->bank_id : null,
						])
					@else
						<a href="{{ url('bank') }}">Click here to add your bank details.</a>
					@endif
				</div>
			@else
				<div class="form-group">
					<label> Wallet Address <span class="text-danger">*</span></label>
					<input class="form-control" maxlength="200" name="walletaddress" id="walletaddress"
						type="text" autocomplete="off"
						value="{{ $isEdit ? $data['withdraw']->wallet_address : '' }}"
						@if ($isEdit) disabled @endif />
				</div>
			@endif
			
			@if ($isEdit && !is_null($data['withdraw']->bank_id) && !is_null($data['withdraw']->bank_proof))
				<div class="form-group">
					<label>
						Withdraw proof : {{ $data['withdraw']->bank_proof }} <a
							href="{{ url('download') . '/' . $data['withdraw']->bank_proof }}"><i
								class="fa fa-download"></i></a>
					</label>
				</div>
			@endif
			<div class="form-group">
				<label>Remarks</label>
				<textarea class="form-control" cols="28" id="remarks" maxlength="400" name="remarks" rows="4"
					@if ($isEdit) disabled @endif>{{ $isEdit ? $data['withdraw']->remarks : '' }}</textarea>
			</div>
			<div class="form-group hide" id="js-otp-div">
				<label> OTP <span class="text-danger">*</span></label>
				<input class="form-control" name="otp" id="js-otp" type="text" autocomplete="off"
					placeholder="Enter your OTP" />
				<span
					style="font-size: 12px;font-weight: bold;padding: 5px;margin-bottom: 10px;color: red;">Note:
					We sent OTP into your register email address, please check.</span>
			</div>			
			
			<div class="form-group clearfix">
				<div class="icheck-success d-inline">
					<input type="checkbox" name="is_withdraw_fee_checked" id="is_withdraw_fee_checked"
						value="1" required="required" @if ($isEdit && $data['withdraw']->is_withdraw_fee_checked == 1) checked @endif />
					<label for="is_withdraw_fee_checked">
						Withdraw fee per transaction
						{{ $data['coin']->withdraw_fee . ' ' . $data['coin']->symbol }}
					</label>
				</div>
			</div>			
            <div class="row">
				<div class="card-footer">
					@if (!$isEdit)
						<a href="javascript://" id="js-submit" data-url='{{ url('send-otp') }}'
							data-submit='{{ route('withdraw') }}' class="btn btn-primary js-progress">
							<i class="fas fa-spinner fa-spin hide" id="js-loader"></i> <span id="js-text">Next</span></a>
					@endif
					<a href="{{ url('withdraw-request') }}" class="btn btn-danger js-progress">Cancel</a>
				</div>
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