@include('layout.home-header')
<div class="header-out-1">
    <div class="container">
        <div class="package-2">
            <div class="package-title"> Request Report</div>
            <div class="e-ou-space">
                <div class="mt-4 mr-5 ml-5">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Amount</th>
                                <th>Final Withdraw amount</th>
                                <th>Withdrawal Date</th>
                                <th style="width: 40px">Status</th>
                                <th style="width: 40px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['withdraws'] as $index => $withdraw)
                                <tr>
                                    <td>{{ ($data['withdraws']->currentPage() - 1) * $data['withdraws']->perPage() + ($index + 1) }}
                                    </td>
                                    <td>{{ $withdraw->amount . ' ' . $withdraw->crypto->symbol }}</td>
                                    <td>{{ $withdraw->final_amount . ' ' . $withdraw->crypto->symbol }}</td>
                                    <td>{{ $withdraw->withdrawal_date }}</td>
                                    @if ($withdraw->status == 1)
                                        <td><span class="badge bg-primary">Payout Pending</span></td>
                                    @elseif($withdraw->status == 2)
                                        <td><span class="badge bg-success">Payout Sent</span></td>
                                    @else
                                        <td><span class="badge bg-danger">Rejected</span></td>
                                    @endif

                                    @php
                                        $requestParams = ['user_id' => 0, 'coin_id' => $withdraw->crypto->id, 'id' => $withdraw->id];
                                    @endphp
                                    <td align="center"><a
                                            href="{{ url('withdraw') . '/' . Crypt::encrypt($requestParams) }}">

                                            <i class="fas fa-eye"></i>
                                        </a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" align="center">No records found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {!! $data['withdraws']->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.home-footer')
