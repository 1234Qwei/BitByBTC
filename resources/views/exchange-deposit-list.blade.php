@include('layout.home-header')
@include('modal.deposit')  
<div class="header-out-1">
    <div class="container"> 
        <div class="exc-box-0">
            <div class="excc-1">
                <div class="exc-ref-ou-0">
                    <div class="exc-ref-ou-1-0"><a id="deposit">Exchange Deposit History</a></div>
                </div>
                <div class="package-2">
                    <div class="e-ou-space">
                        <div class="mt-4 mr-5 ml-5">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Exchange type</th>
                                        <th>Deposit Currency</th>
                                        <th>Deposit Coin</th>
                                        <th>Requested Date</th>
                                        <th style="width: 40px">Status</th> 
                                        <th style="width: 40px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['exchange'] as $index => $exchange)
                                        <tr>
                                            <td>{{ ($data['exchange']->currentPage() - 1) * $data['exchange']->perPage() + ($index + 1) }}
                                            </td>
                                            <td>{{ $exchange->exchange_type == 1 ? 'Deposit' : 'Swap' }}</td>
                                            <td>{{ $exchange->deposit->coin ?? '---' }}</td>
                                            <td>{{ $exchange->deposit_coin ?? 0 }} @if ($exchange->deposit)
                                                    {{ $exchange->deposit->symbol ?? '---' }}
                                                @endif
                                            </td>
                                            <td>{{ $exchange->created_at }}</td>
                                            @if (is_null($exchange->transaction_id) && is_null($exchange->bank_proof) && $exchange->status == 0)
                                                <td><span class="badge bg-warning" data-toggle="tooltip"
                                                        title="Please update the transaction hash / proof is important">Proof
                                                        is Pending</span></td>
                                            @elseif($exchange->status == 0)
                                                <td><span class="badge bg-primary">Not Verified</span></td>
                                            @elseif($exchange->status == 1)
                                                <td><span class="badge bg-success">Approved</span></td>
                                            @else
                                                <td><span class="badge bg-danger">Rejected</span></td>
                                            @endif
                                            <td align="center">
                                                <a href="javascript://" data-toggle="tooltip" title="Click to View"
                                                    id="jsDepositAction" data-id="{{ $exchange->id }}"
                                                    data-url="{{ url('load-view') }}">
                                                    @if (!$exchange->is_stacking &&
                                                        is_null($exchange->transaction_id) &&
                                                        is_null($exchange->bank_proof) &&
                                                        !$exchange->is_referral)
                                                        <i class="fa fa-edit"></i>
                                                    @else
                                                        <i class="fa fa-eye"></i>
                                                    @endif
                                                </a>
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
                            {!! $data['exchange']->appends(Request::except('page'))->render() !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.home-footer')
