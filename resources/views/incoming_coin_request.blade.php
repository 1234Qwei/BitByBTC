@include('layout.home-header')
@include('modal.coin-status-view')
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title">Incoming coin request</div>
      <div class="e-ou-space">
      <div class="mt-4 mr-5 ml-5">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
			  <th>Buyer Name</th>
              <th>Coin Name</th>
              <th>Qty</th>
              <th>Total Amount</th>
              <th style="width: 40px">Status</th>
              <th style="width: 40px">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($data['incoming_coin_request'] as $index => $order_requests)
				@foreach($order_requests->getRequestedCoinDetails as $orderinfo)
					<tr>
					  <td>1</td>
					  <td>{{ $order_requests->getUserDetails->first_name }}</td>
					  <td>{{ $orderinfo->coin_id }}</td>
					  <td>{{ $orderinfo->coin_volume }}</td>
					  <td>{{ number_format($orderinfo->initial_price * $orderinfo->coin_volume, 2) }}</td>
					  @if($order_requests->status == 1)
					  <td><span class="badge bg-primary">proof Document Need</span></td>
					  @elseif($order_requests->status == 2)
					  <td><span class="badge bg-info">Not Approved</span></td>
					 @elseif($order_requests->status == 3)
					  <td><span class="badge bg-success">Approved</span></td>
					  @else
					  <td><span class="badge bg-danger">Rejected</span></td>
					  @endif
					  <td align="center">
              
						<a href="{{ url('get-requested-coin-status') . '/' .  Crypt::encrypt($order_requests->id) }}" data-toggle="tooltip"
						  title="Click to edit">
						  <i class="fa fa-edit"></i>
						</a>
					  </td>
					</tr>
				@endforeach
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