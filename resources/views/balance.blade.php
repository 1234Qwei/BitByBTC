@include('layout.home-header')
<div class="header-out-1">
    <div class="container">
        <div class="exc-box-0">
            <!--portfolio-start-->
            <div class="excc-1">
                <div class="exc-ref-ou-0">
                    <div class="exc-ref-ou-1-0"><a id="deposit">Exchange Balance</a></div>
                </div>
                <div class="package-2">
                    <div class="e-ou-space">
                        <div class="mt-4 mr-5 ml-5">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th style="width: 200px">Coin</th>
                                        <th class="text-center" style="width: 100px">Balance</th>
                                        @if (Auth::user()->id !== 1)
                                            <th class="text-center" style="width: 300px;">Action</th>
                                        @endif
                                    </tr> 
                                </thead>
                                <tbody>
                                    @forelse($data['coins'] as $index => $coin)
                                        @php
                                            $balance = App\Http\Controllers\ExchangeController::getCoinBalance($coin->coin_id);
                                
                                            $requestParams = ['user_id' => Auth::user()->id, 'coin_id' => $coin->coin_id, 'deposit_currency' => $coin->coin_id, 'id' => 0];
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $coin->coin }}</td>
                                            <td class="text-center">{{ $balance . ' ' . $coin->symbol }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">   
                                                    <?php echo $coin->coin_id;?>
                                                       <a role="button" 
                                                        href="{{ route('exchange', ['depositCoin' => $coin->coin_id]) }}"
                                                        class="btn btn-success mr-2">Deposit</a>
                                                       <a role="button"
                                                         href="{{ url('withdraw') . '/' . Crypt::encrypt($requestParams) }}"
                                                        class="btn btn-danger mr-2">Withdraw</a>
                                                        <a role="button"
                                                        href="{{ url('sell') . '/' . Crypt::encrypt($requestParams) }}"
                                                        class="btn btn-info">Sell</a> 
                                                        <a role="button"
                                                         href="{{ url('stacking-contract')}}"
                                                         class = "@if($balance < 1000 ) btn btn-warning mx-2 @else btn btn-warning disabled mx-2 @endif" 
                                                        class="btn btn-info mx-2">Stack</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">No records found!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">

                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!--portfolio-end--->
        </div>
    </div>
</div>
@push('scripts')
@endpush
@include('layout.home-footer')
