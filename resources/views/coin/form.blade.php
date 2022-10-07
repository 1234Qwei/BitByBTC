@include('layout.home-header')
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title"> Coin details</div>
      <div class="e-ou-space">
        <div class="p-5">
                <form id="coinUpdateForm" method="post" action="{{ route('coin') }}">
                    <input type="hidden" name="id" value="{{ $data['coin']['id'] ?? 0 }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label> Coin <span class="text-danger">*</span></label>
                                <input class="form-control" name="coin" type="text" autocomplete="off"
                                    value="{{ $data['coin']['coin'] ?? old('coin') }}" id="coin" />
                                @error('coin')
                                    <span id="coin-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4 ">
                                <label> Symbol <span class="text-danger">*</span></label>
                                <input class="form-control" name="symbol" type="text" autocomplete="off"
                                    value="{{ $data['coin']['symbol'] ?? old('symbol') }}" id="symbol" />
                                @error('symbol')
                                    <span id="symbol-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4 ">
                                <label> Coin ID <small> <em>(Coingecko ID)</em></small> <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" name="coin_id" type="text" autocomplete="off"
                                    value="{{ $data['coin']['coin_id'] ?? old('coin_id') }}" id="coin_id" />
                                @error('coin_id')
                                    <span id="coin_id-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label> Min Withdraw <span class="text-danger">*</span></label>
                                <input class="form-control" name="withdraw_min" type="text" autocomplete="off"
                                    value="{{ $data['coin']['withdraw_min'] ?? old('withdraw_min') }}"
                                    id="withdraw_min" />
                                @error('withdraw_min')
                                    <span id="withdraw_min-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label> Max Withdraw<span class="text-danger">*</span></label>
                                <input class="form-control" name="withdraw_max" type="text" autocomplete="off"
                                    value="{{ $data['coin']['withdraw_max'] ?? old('withdraw_max') }}"
                                    id="withdraw_max" />
                                @error('withdraw_max')
                                    <span id="withdraw_max-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label> Withdraw Fee <span class="text-danger">*</span></label>
                                <input class="form-control" name="withdraw_fee" type="text" autocomplete="off"
                                    value="{{ $data['coin']['withdraw_fee'] ?? old('withdraw_fee') }}"
                                    id="withdraw_fee" />
                                @error('withdraw_fee')
                                    <span id="withdraw_fee-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label> Deposit address <span class="text-danger">*</span></label>
                                <input class="form-control" name="address" type="text" autocomplete="off"
                                    value="{{ $data['coin']['address'] ?? old('address') }}" id="address" />
                                @error('address')
                                    <span id="address-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <h5>Stacking Options</h5>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label> Stacking <span class="text-danger">*</span></label>
                                <input type="checkbox" name="is_stacking" data-bootstrap-switch data-off-color="danger"
                                    data-on-color="success" class="text-right" data-on-text="+" data-off-text="-"
                                    data-size="normal" value="1" @if ($data['coin'] && $data['coin']['is_stacking'] == 1) checked @endif>
                                <input class="form-control" name="stacking_address" type="text" autocomplete="off"
                                    value="{{ $data['coin']['stacking_address'] ?? old('stacking_address') }}"
                                    id="stacking_address" />
                                @error('stacking_address')
                                    <span id="stacking_address-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <h5>Pricing & Other Options</h5>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label> INR to Coin Percentage <span class="text-danger">*</span></label>
                                <input type="checkbox" name="inr_to_coin_percentage_option" data-bootstrap-switch
                                    data-off-color="danger" data-on-color="success" class="text-right"
                                    data-on-text="+" data-off-text="-" data-size="normal" value="1"
                                    @if ($data['coin'] && $data['coin']['inr_to_coin_percentage_option'] == 1) checked @endif>
                                <input class="form-control" name="inr_to_coin_percentage" type="text"
                                    autocomplete="off"
                                    value="{{ $data['coin']['inr_to_coin_percentage'] ?? old('inr_to_coin_percentage') }}"
                                    id="inr_to_coin_percentage" />
                                @error('inr_to_coin_percentage')
                                    <span id="inr_to_coin_percentage-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="form-group col-md-4">
                                <label> Coin To INR Percentage <span class="text-danger">*</span></label>
                                <input type="checkbox" name="coin_to_inr_percentage_option" data-bootstrap-switch
                                    data-off-color="danger" data-on-color="success" class="text-right"
                                    data-on-text="+" data-off-text="-" data-size="normal" value="1"
                                    @if ($data['coin'] && $data['coin']['coin_to_inr_percentage_option'] == 1) checked @endif>
                                <input class="form-control" name="coin_to_inr_percentage" type="text"
                                    autocomplete="off"
                                    value="{{ $data['coin']['coin_to_inr_percentage'] ?? old('coin_to_inr_percentage') }}"
                                    id="coin_to_inr_percentage" />
                                @error('coin_to_inr_percentage')
                                    <span id="coin_to_inr_percentage-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label> Coin To Coin Percentage <span class="text-danger">*</span></label>
                                <input type="checkbox" name="coin_to_coin_percentage_option" data-bootstrap-switch
                                    data-off-color="danger" data-on-color="success" class="text-right"
                                    data-on-text="+" data-off-text="-" data-size="normal" value="1"
                                    @if ($data['coin'] && $data['coin']['coin_to_coin_percentage_option'] == 1) checked @endif>
                                <input class="form-control" name="coin_to_coin_percentage" type="text"
                                    autocomplete="off"
                                    value="{{ $data['coin']['coin_to_coin_percentage'] ?? old('coin_to_coin_percentage') }}"
                                    id="coin_to_coin_percentage" />
                                @error('coin_to_coin_percentage')
                                    <span id="coin_to_coin_percentage-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label> Fetching From API : <span class="text-danger">*</span></label>
                                <div class="clearfix">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="fetch_pricing_from_api" id="enable" value="1"
                                            @if ($data['coin'] && $data['coin']['fetch_pricing_from_api'] == 1) checked @endif />
                                        <label for="enable">
                                            Enable
                                        </label>
                                    </div>
                                    <div class="ml-3 icheck-success d-inline">
                                        <input type="radio" name="fetch_pricing_from_api" id="disable" value="0"
                                            @if ($data['coin'] && !$data['coin']['fetch_pricing_from_api']) checked @endif />
                                        <label for="disable">
                                            Disable
                                        </label>
                                    </div>
                                    @error('fetch_pricing_from_api')
                                        <span id="fetch_pricing_from_api-error"
                                            class="error invalid-feedback display-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label> Deposit Enable/ Disable : <span class="text-danger">*</span></label>
                                <div class="clearfix">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="exchange_type" id="d_enable" value="1"
                                            @if ($data['coin'] && $data['coin']['exchange_type'] == 1) checked @endif />
                                        <label for="d_enable">
                                            Enable
                                        </label>
                                    </div>
                                    <div class="ml-3 icheck-success d-inline">
                                        <input type="radio" name="exchange_type" id="d_disable" value="2"
                                            @if ($data['coin'] && $data['coin']['exchange_type'] == 2) checked @endif />
                                        <label for="d_disable">
                                            Disable
                                        </label>
                                    </div>
                                    @error('exchange_type')
                                        <span id="exchange_type-error"
                                            class="error invalid-feedback display-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Status: <span class="text-danger">*</span></label>
                                <div class="clearfix">
                                    <div class="icheck-success d-inline">
                                        <input type="radio" name="status" id="approve" value="1"
                                            @if ($data['coin'] && $data['coin']['status'] == 1) checked @endif />
                                        <label for="approve">
                                            Enable
                                        </label>
                                    </div>
                                    <div class="ml-3 icheck-success d-inline">
                                        <input type="radio" name="status" id="reject" value="2"
                                            @if ($data['coin'] && $data['coin']['status'] == 2) checked @endif />
                                        <label for="reject">
                                            Disable
                                        </label>
                                    </div>
                                    @error('status')
                                        <span id="status-error"
                                            class="error invalid-feedback display-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>INR Price: </label>
                                <input class="form-control" name="inr_price" type="text" autocomplete="off"
                                    value="{{ $data['coin']['inr_price'] ?? old('inr_price') }}" id="inr_price" />
                                @error('inr_price')
                                    <span id="inr_price-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Order Price: </label>
                                <input class="form-control" name="sort_order" type="number" autocomplete="off"
                                    value="{{ $data['coin']['sort_order'] ?? old('sort_order') }}" id="sort_order"
                                    required />
                                @error('sort_order')
                                    <span id="sort_order-error"
                                        class="error invalid-feedback display-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary js-progress"
                            id="js-update">{{ $data['coin'] && $data['coin']->id ? 'Update' : 'Add' }}</button>
                        <a href="{{ url('coins') }}" class="btn btn-danger js-progress">Cancel</a>
                    </div>
                </form>
        </div>
      </div>
    </div>
  </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("input[data-bootstrap-switch]").bootstrapSwitch('state', $(this).prop('checked'));
        });

        $("#coinUpdateForm").validate({
            rules: {
                coin: {
                    required: true,
                },
                symbol: {
                    required: true,
                },
                coin_id: {
                    required: true,
                },
                address: {
                    required: true,
                },
                withdraw_min: {
                    required: true,
                    number: true,
                    max: function() {
                        return Number(
                            $("body").find('input[name="withdraw_min"]').val()
                        );
                    },
                },
                withdraw_max: {
                    required: true,
                    number: true,
                    min: function() {
                        return Number($("body").find("#withdraw_min").val());
                    },
                },
                withdraw_fee: {
                    required: true,
                    number: true,
                },
                exchange_type: {
                    required: true,
                },
                fetch_pricing_from_api: {
                    required: true,
                },
                inr_price: {
                    required: function() {
                        return (Number($("body").find("[name='fetch_pricing_from_api']:checked").val()) == 0);
                    },
                    min: function() {
                        return ((Number($("body").find("[name='fetch_pricing_from_api']:checked").val()) ==
                            0) && !Number($("body").find("[name='inr_price']").val()));
                    }
                },
            },
            messages: {
                exchange_type: {
                    required: "Please select a deposit is enabled/disabled",
                },
                fetch_pricing_from_api: {
                    required: "Please select a pricing is fecteching from the API is enabled/disabled",
                },
                withdraw_min: {
                    min: "The minimum amount has to be lower than {0}",
                },
                withdraw_min: {
                    max: "The minimum amount has to be higher than {0}",
                },
                inr_price: {
                    required: "The enter the INR price",
                    min: "The minimum amount has to be higher than 0",
                }
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
            submitHandler: function(form) {
                // do other things for a valid form
                form.submit();
                $("body")
                    .find("#js-update")
                    .text("Loading...")
                    .prop("disabled", true);
            },
        });
    </script>
@endpush
@include('layout.home-footer')