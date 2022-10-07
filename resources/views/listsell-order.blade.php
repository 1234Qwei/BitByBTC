@include('layout.home-header')
<div class="header-out-1">
    <div class="container">
        <div class="package-2">
            <div class="package-title"> {{ $data['pageName'] }}</div>
            <div class="mt-4 mr-5 ml-5">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Coin Name</th>
                            <th class="text-center">Volume</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Order Status</th>
                            <th class="text-center">Created Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['sellOrder'] as $index => $sellorderval)
                            <tr>
                                <td>{{ $sellorderval->getcoin->coin . ' ' . $sellorderval->getcoin->symbol }}</td>
                                <td> {{ $sellorderval->coin_volume }}
                                </td>
                                <td>{{ $sellorderval->initial_price . ' Rs' }}</td>
                                @if ($sellorderval->status == 1 || $sellorderval->status == 2)
                                    <td><span class="badge bg-danger">Active</span></td>
                                @elseif($sellorderval->status == 3)
                                    <td><span class="badge bg-success">Completed</span></td>
                                @endif
                                <td>{{ date('Y-m-d', strtotime($sellorderval->created_at)) }}
                                </td>
                                <td align="center">
                                    @if ($sellorderval->status == 3)
                                        <i class="fa fa-eye"></i>
                                    @else
                                        <a href="{{ url('edit-sellorder') . '/' . $sellorderval->id }}"
                                            data-toggle="tooltip" title="Click to edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif
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
        </div>
    </div>
</div>
</div>
@include('layout.home-footer')
