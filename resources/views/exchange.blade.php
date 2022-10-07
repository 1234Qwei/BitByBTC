@include('layout.home-header')
<div class="header-out-1">
    <div class="container">
        <div class="exc-box-1"> 
            <!--portfolio-start-->
            <div class="excc-1">
                <div class="exc-ref-ou-1"> 
                    <div class="exc-ref-ou-1-0"><a id="deposit">Deposit</a></div>

                </div>
                <form method="POST" action="{{ route('exchange') }}" id="exchangeDepositForm" autocomplete="off">@csrf
                    <input type="hidden" id="deposit_min" value="" />
                    <input type="hidden" name="exchange_type" value="1" />
                    <div class="depositopen show">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <select class="enq-input city-space-2" name="deposit_currency" id="deposit_currency"
                                    required="required" data-url="{{ url('deposit-address') }}">
                                    <option value="">Select Currency</option>
                                    @foreach ($data['depositcoin'] as $dd_coin)
                                        <option @if (old('deposit_currency') == $dd_coin->coin_id) selected @endif
                                            coinSymbol="{{ $dd_coin->symbol }}" value="{{ $dd_coin->coin_id }}">
                                            {{ $dd_coin->coin }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="enq-input purple_placeholder" required name="deposit_coin"
                                    id="deposit_coin" value="{{ old('deposit_coin') ?? '' }}"
                                    placeholder="Deposit Coin" />
                            </div>
                        </div>
                        <div id="fiat_currency_address" class="hide">
                            @include('modal.inr-deposit', [
                                'banks' => $data['banks'],
                                'bank_id' => null,
                            ])
                        </div>
                        <div class="row address_section">
                            <div class="form-group col-md-10">
                                <input type="text" class="enq-input" name="walletaddress"
                                    value="{{ old('walletaddress') ?? '' }}" readonly id="walletaddress"
                                    required="required">
                            </div>
                            <div class="col-md-2" style="margin-top: 15px;">
                                <span class="input-group-btn">
                                    <button class="cpy-btn btn btn-default" type="button"
                                        data-clipboard-target="#walletaddress">
                                        Copy
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group text-center mt-3 address_section">
                            <label>OR <br /> SCAN HERE!</label>
                            <div class="justify-content">
                                <div class="deposit-code-loader hide" id="depositCodeLoader">
                                    <i class="fas fa-sync-alt fa-spin fa-3x deposit-loader" aria-hidden="true"></i>
                                </div>
                                <img src="https://chart.googleapis.com/chart?chs=250x250&chld=M|0&cht=qr&chl={{ old('walletaddress') ?? $data['wallet'] }}&choe=UTF-8"
                                    id="depositBarCode">
                            </div>
                        </div>
                        <div class="row pull-right">
                            <button type="submit" class="btn btn-primary btn-deposit ex-deposit-btn"
                                @if (!old('deposit_coin')) disabled="disabled" @endif id="deposit_button">Deposit
                                !</button>
                        </div>
                    </div>
                </form>
            </div>
            <!--portfolio-end--->
        </div>
    </div>
</div>
@push('scripts')
    @if (Request::is('exchange') && Request::get('depositCoin'))
        var seletedCoin = "{{ request()->get('depositCoin') }}";
        $('body').find('#deposit_currency').val(seletedCoin).change();
    @endif
@endpush
@include('layout.home-footer')
