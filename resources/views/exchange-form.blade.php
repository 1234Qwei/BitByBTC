@include('layout.home-header')
@include('modal.otp')
@php use App\Models\UserBank;@endphp
<style>
    * {
        box-sizing: border-box;
    }

    /* Create two equal columns that floats next to each other */
    .column {
        float: left;
        width: 50%;
        padding: 10px;
        height: 300px;
        /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    .error {
        color: red;
    }
</style>
<div class="header-out-1">
    <div class="container">
        <div class="package-2">
            <div class="package-title"> Sell Order</div>
            <div class="e-ou-space">
                <div class="p-5">
                    <form id="sellUpdateForm" method="post" action="javascript:void(0)">
                        @csrf
                        <input type="hidden" name="sellorder_id" value="{{ $data['order_id'] }}">
                        <input type="hidden" id="sellformaction" value="{{ route('coin-request') }}">
                        <div class="row">
                            <div class="column">
                                <div class="row">
                                    <label> Payment Option :<span class="text-danger"></span></label></br>
                                </div>
                                @php $total_amount = 0 @endphp
                                @foreach ($data['exchange_details']->getpaymentoption as $bankDetailsval)
                                    <?php $bankdetails = UserBank::getbankdetail($bankDetailsval->user_id, $bankDetailsval->bank_id);
                                    
                                    ?>
                                    <div class="form-group bank-detail">
                                        <label>{{ $bankdetails->name }}<span class="text-danger"></span></label>

                                        <br>
                                        @if ($bankdetails->selected_account == 1)
                                            <div class="form-group">
                                                <p>Account Name : {{ $bankdetails->name }}</p>
                                                </br>
                                                <label for="is_primary">Bank Name :
                                                    {{ $bankdetails->bank_name }}</label><br>
                                                <label for="is_primary"> Account Number:
                                                    {{ $bankdetails->account_number }}</label><br>
                                                <label for="is_primary"> Ifsc Code:
                                                    {{ $bankdetails->ifsc_code }}</label><br>
                                                <label for="is_primary"> Account Type:
                                                    {{ $bankdetails->account_type == 1 ? 'Saving' : 'Current' }}</label><br>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="is_primary">Bank Type :
                                                    {{ $bankdetails->name }}</label></br>
                                                <label for="is_primary"> Account Number:
                                                    {{ $bankdetails->account_number }}</label></br>
                                                <label for="is_primary"> Ifsc Code:
                                                    {{ $bankdetails->ifsc_code }}</label></br>
                                                <label for="is_primary"> Account Type:
                                                    {{ $bankdetails->account_type == 1 ? 'Saving' : 'Current' }}</label>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                <div class="form-group">
                                    <p>Remarks : <?php echo $data['exchange_details']->remark; ?></p>
                                </div>
                            </div>
                            <?php $total_amount = $data['exchange_details']->coin_volume * $data['exchange_details']->initial_price; ?>
                            <div class="column">
                                <div class="form-group">
                                    <label>Coin Name</label>
                                    <h2 class="mt-4 mr-5 ml-5" style="font-weight: 500">
                                        {{ strtoupper($data['exchange_details']->coin_id) }}</h2>
                                </div>
                                <div class="form-group">
                                    <label>Availability</label>
                                    <h2 class="mt-4 mr-5 ml-5" style="font-weight: 500">
                                        {{ $data['exchange_details']->coin_volume }}</h2>
                                </div>
                                <div class="form-group">
                                    <label>Coin Price</label>
                                    <h2 class="mt-4 mr-5 ml-5" style="font-weight: 500">
                                        {{ $data['exchange_details']->initial_price }}</h2>
                                </div>
                                <div class="form-group">
                                    <label>Currency</label>
                                    <h2 class="mt-4 mr-5 ml-5" style="font-weight: 500">INR</h2>
                                </div>
                                <div class="form-group paidsecion">
                                    <label>Total paid amount :</label>
                                    <h2 class="mt-4 mr-5 ml-5" style="font-weight: 500">
                                        {{ number_format($total_amount, 2) }}</h2>

                                </div>
                            </div>
                        </div>

                </div>
                <div class="row" style="margin-top: 145px;">
                    <div class="col-md-4 col-md-offset-8 pull-right">
                        <button type="submit" data-form="exchangeform"
                            class="btn-md btn btn-warning js-progress sellorder" id="js-update"
                            data-action="{{ url('sendotp-mail') }}">Buy</button>
                        <a href="{{ url('exchange-list') }}" class="btn-md btn btn-danger">Cancel</a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@push('scripts')
    <script></script>
@endpush
@include('layout.home-footer')
