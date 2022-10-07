@include('layout.home-header')
<div class="header-out-2">
   <div class="container">
    <div class="exc-box-0">
        <div class="excc-1">
           <div class="exc-ref-ou-0 border-1"><h3 class="exc-ref-ou-1-0">Stacking Contract</h3></div>
           <form action="{{ route('stacking-contract') }}" method="post">
             @csrf
             <div class="package-2">
                <div class="e-ou-space">
                    <div class="mt-2 mr-5 ml-5"> 
                        <div class="border-1" >
                            <div class="col-md-6">
                                <label class="my-3" style='font-size:15px;'>Coin :</label>
                                <select class="form-control px-4 mx-auto mb-2" name="stacking_currency" id="stacking_currency"
                                required="required" data-url="{{ url('deposit-address') }}">
                                <option class='mt-2' value="0">Select Currency</option>
                                @foreach ($data['stackingCoin'] as $dd_coin)
                                <option value='{{$dd_coin->id}}' @if ($dd_coin->id) selected @endif
                                    coinSymbol="{{ $dd_coin->symbol }}" value="{{ $dd_coin->id }}">
                                    {{ $dd_coin->coin }}
                                </option>
                                @endforeach
                            </select>
                            <?php echo $dd_coin->id;?>
                            </div>
                            <div class="col-6 mt-2 e-ou-space">
                               <label class="my-2 px-2" style='font-size:15px;'>Packages :</label>
                               <div class="my-4" style="margin-left:8rem;">
                                 @foreach ($data['packages'] as $index => $package)
                                    <div class="col-md-4 form-group clearfix">
                                        <div class="icheck-warning d-inline ">
                                            <input style="border-radius: 100%;height: 15px; width: 15px;" type="radio" class="px-6" name="package" id="{{ $package->id }}"
                                            value="{{ $package->id }}"
                                            @if (!$index) checked @endif />
                                            <label for="{{ $package->id }}" class='mt-2' style='font-weight:500; font-size:14px;'>
                                             {{ number_format($package->coin) . ' ' . $dd_coin->symbol }}
                                            </label>
                                        </div>
                                    </div>
                                 @endforeach
                                </div>
                            </div>
                            <div class="col-6 my-1 e-ou-space">
                               <label class="my-2 px-2" style='font-size:15px;'>Terms :</label>
                               <div style="margin-left:8rem;">
                                 @foreach ($data['packageTerms'] as $index => $terms)
                                   <div class="col-md-4 mx-auto form-group clearfix">
                                      <div class="icheck-primary d-inline clearfix">
                                         <input style="border-radius: 100%; height: 15px; width: 15px;" type="radio" name="term"
                                            id="{{ $terms->asset_id . ' - ' . $terms->id }}"
                                            value="{{ $terms->id }}"
                                            @if (!$index) checked @endif />
                                            <label style='font-weight:500; font-size:14px;' for="{{ $terms->asset_id . ' - ' . $terms->id }}">
                                            {{ number_format($terms->duration) }} Month ( {{ $terms->interest }}%
                                            )
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                           </div>
                           <div class="card-footer">
                                <div class="text-right mx-2 px-1">
                                    <a href="{{ url()->previous() }}" role="button" class="btn btn-danger ms-4">Cancel </a>
                                    <button type="submit" class="btn btn-primary  mx-2">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
           </form>
        </div>
    </div>
   </div>
</div>
@include('layout.home-footer')