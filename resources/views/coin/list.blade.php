@include('layout.home-header')
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title"> Bank Account Details</div>
      <div class="e-ou-space">
          <div class="col-md-2 col-md-offset-10 mr-2 mt-2 mb-2">
                            <a href="{{ url('coin-settings') }}" class="btn btn-primary pull-right"><i
                                    class="fa fa-cogs"></i> Exchange Settings</a>
                            <a href="{{ url('coin') }}" class="btn btn-primary pull-right"><i
                                    class="fa fa-plus"></i> Add Asset</a>
          </div>
      </div>
      <div class="mt-4 mr-5 ml-5">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Assets</th>
                                    <th class="text-center" colspan="6">Deposit / Withdraw</th>
                                </tr>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center">Minimum</th>
                                    <th class="text-center">Maximum</th>
                                    <th class="text-center">Fee</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">API Status</th>
                                    <th class="text-center">Stacking</th>
                                    <th class="text-center">Order</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['coins'] as $index => $coin)
                                    <tr>
                                        <td>{{ ($data['coins']->currentPage() - 1) * $data['coins']->perPage() + ($index + 1) }}
                                        </td>
                                        <td>{{ $coin->coin }}</td>
                                        <td class="text-center">{{ $coin->withdraw_min . ' ' . $coin->symbol }}
                                        </td>
                                        <td class="text-center"> {{ $coin->withdraw_max . ' ' . $coin->symbol }}
                                        </td>
                                        <td class="text-center">{{ $coin->withdraw_fee . ' ' . $coin->symbol }}
                                        </td>
                                        @if ($coin->status == 2)
                                            <td><span class="badge bg-danger">In Active</span></td>
                                        @elseif($coin->status == 1)
                                            <td><span class="badge bg-success">Active</span></td>
                                        @endif
                                        @if ($coin->fetch_pricing_from_api)
                                            <td><span class="badge bg-success">Enabled</span></td>
                                        @elseif($coin->status == 1)
                                            <td><span class="badge bg-danger">Disabled</span></td>
                                        @endif
                                        @if ($coin->is_stacking)
                                            <td><span class="badge bg-success">Enabled</span></td>
                                        @else
                                            <td><span class="badge bg-danger">Disabled</span></td>
                                        @endif
                                        <td>{{ $coin->sort_order }}</td>
                                        <td align="center">
                                            <a href="{{ url('coin') . '/' . $coin->id }}" data-toggle="tooltip"
                                                title="Click to edit">
                                                <i class="fa fa-edit"></i>
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
    </div>
  </div>
</div>
</div>
@include('layout.home-footer')