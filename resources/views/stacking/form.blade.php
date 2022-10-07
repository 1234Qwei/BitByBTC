@include('layout.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('stacking-contract') }}" method="post" id="stackingForm">
                    <input type="hidden" name="is_existing" value="1" /> 
                    @csrf
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <label class="col-2">Coin :</label>
                            <div class="form-group col-md-6">
                                <select class="form-control" 
                                    required="required" data-url="{{ url('deposit-address') }}" disabled>
                                    @foreach ($data['stackingCoin'] as $dd_coin)
                                        <option @if ($data['stacking_currency'] == $dd_coin->id) selected @endif
                                            coinSymbol="{{ $dd_coin->symbol }}" value="{{ $dd_coin->id }}">
                                            {{ $dd_coin->coin }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <label class="col-2">Packages :-</label>
                            <div class="col-10">
                                <div class="row">
                                    @foreach ($data['packages'] as $index => $package)
                                        <div class="col-4 form-group clearfix">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="package" id="{{ $package->id }}"
                                                    value="{{ $package->id }}"
                                                    @if (!$index) checked @endif />
                                                <label for="{{ $package->id }}">
                                                    {{ number_format($package->coin) . ' ' . $data['stacking_symbol'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <label class="col-2">Terms :-</label>
                            @foreach ($data['packageTerms'] as $index => $terms)
                                <div class="col-4 form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" name="term"
                                            id="{{ $terms->asset_id . ' - ' . $terms->id }}"
                                            value="{{ $terms->id }}"
                                            @if (!$index) checked @endif />
                                        <label for="{{ $terms->asset_id . ' - ' . $terms->id }}">
                                            {{ number_format($terms->duration) }} Month ( {{ $terms->interest }}%
                                            )
                                        </label>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <a href="{{ url()->previous() }}" role="button" class="btn btn-danger">Cancel </a>
                            <button type="submit" class="btn btn-primary" id="submit-stacking">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).on('submit', '#stackingForm', function(event) {
            $("body").find(".btn").addClass('disabled').prop("disabled ", true);
            $("body").find("#submit-stacking").text("Loading...");
            return true;
        });
    </script>
@endpush
@include('layout.footer')
