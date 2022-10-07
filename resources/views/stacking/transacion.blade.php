@include('layout.header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction History</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                @if (Auth::user()->id === 1)
                                    <th>User</th>
                                @endif
                                <th>Date</th>
                                <th>Transaction Type</th>
                                <th>Amount</th>
                                <th align="center">Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['transactionhistorys'] as $index => $transactionhistorys)
                                <tr>
                                    <td>{{ ($data['transactionhistorys']->currentPage() - 1) * $data['transactionhistorys']->perPage() + ($index + 1) }}
                                    </td>
                                    @if (Auth::user()->id === 1)
                                        <td>{{ $transactionhistorys->user->name ?? '' }} (
                                            {{ $transactionhistorys->user->username ?? '' }} )</td>
                                    @endif
                                    <td>{{ $transactionhistorys->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $transactionhistorys->transaction_type == 1 ? 'Creadit' : 'Debit' }}</td>
                                    <td>{{ number_format($transactionhistorys->amount) }}</td>
                                    @if ($transactionhistorys->withdraw_id !== null)
                                        @if ($transactionhistorys->withdraw->status == 1)
                                            <td><span class="badge bg-primary">Payout Pending</span></td>
                                        @elseif($transactionhistorys->withdraw->status == 2)
                                            <td><span class="badge bg-success">Payout Sent</span></td>
                                        @else
                                            <td><span class="badge bg-danger">Rejected</span></td>
                                        @endif
                                    @else
                                        <td><span class="badge bg-success">Payout Creadited</span></td>
                                    @endif
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
                    {!! $data['transactionhistorys']->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
