@include('layout.home-header')
@include('modal.otp')
<div class="header-out-1">
    <div class="container">
        <div class="package-2">
            <div class="package-title"> Sell Order</div>
            <div class="e-ou-space">
                <div class="p-5" style="margin-left:20%">
                    <form id="sellUpdateForm" method="post" action="javascript:void(0)">
                        @csrf
                        <input type="hidden" id="sellformaction" value="{{ route('sell-order') }}">
                        <input type="hidden" name="coin_id" value="{{ $coinData->coin_id }}">
                        <input type="hidden" name="deposit_currency" value="{{ $coinData->coin_id }}">
                        <input type="hidden" name="hid"
                            value="@if (isset($sellorder)) {{ $sellorder->id }} @endif">
                        <input id="pagetype" type="hidden"
                            value="@if (isset($sellorder)) {{ 'edit' }} @else 'new' @endif" />
                        <input id="existingcoin" type="hidden"
                            value="@if (isset($sellorder)) {{ $sellorder->coin_volume }} @endif" />
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Coin Name</label>
                                <input class="form-control" type="text" readonly
                                    value="{{ strtoupper($coinData->coin) }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Available</label>
                                <input class="form-control" type="text" id="totalcoin" readonly autocomplete="off"
                                    value="{{ $balance }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label> Coin Volume<span class="text-danger">*</span></label>
                                <input class="form-control" name="coin_volume" id="coin_volume" type="text"
                                    autocomplete="off"
                                    value="@if (isset($sellorder)) {{ $sellorder->coin_volume }} @endif"
                                    required />
                                <span class="error invalid-feedback" id="coinlimit_err">
                                    @error('coin_volume')
                                        <span id="coin_volume-error"
                                            class="error invalid-feedback display-block">{{ $message }}</span>
                                    @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label> Initial Price <span class="text-danger">*</span></label>
                                <input class="form-control" name="initial_price" id="initial_price" type="text"
                                    autocomplete="off"
                                    value="@if (isset($coinprice)) {{ $coinprice }} @endif" />
                                <span class="error invalid-feedback" id="initial_price_err">
                                    @error('initial_price')
                                        <span id="initial_price-error"
                                            class="error invalid-feedback display-block">{{ $message }}</span>
                                    @enderror
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="form-group col-md-6 bank-detail">
                                <label> Payment Option<span class="text-danger"></span></label>
                            </div>
                            @foreach ($bankDetails as $bankDetailsval)
                                <br>
                                <div class="form-group col-md-12" style="margin-left: 10%;">
                                    <div class="icheck-success d-inline">
                                        <input type="checkbox" name="banks[]" id="is_primary"
                                            value="{{ $bankDetailsval->id }}"
                                            @if (isset($userbanks)) @if (in_array($bankDetailsval->id, $userbanks)) {{ 'checked' }} @endif
                                            @endif>
                                        <label for="is_primary">{{ $bankDetailsval->accountType->label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label> Remarks<span class="text-danger"></span></label>
                                <textarea name="remark" id="remark">
                  @if (isset($sellorder)) {{ $sellorder->remark }} @endif
                 </textarea>
                                <span class="error invalid-feedback" id="remark_err">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-8 pull-right">
                                <button type="submit" class="btn-md btn btn-warning js-progress sellorder"
                                    id="js-update" data-action="{{ url('sendotp-mail') }}">Post Adv</button>
                                <a href="{{ url('list-sellorder') }}"
                                    class="btn-md btn btn-danger js-progress">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#sellUpdateForm').on('submit', function(e) {
                $('#sellUpdateForm').submit();
                // e.preventDefault();
                // $("#otpVerification").modal("toggle");
            });
        });
    </script>
@endpush

@include('layout.home-footer')
