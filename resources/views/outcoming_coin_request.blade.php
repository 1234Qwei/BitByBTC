@include('layout.home-header')
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title">Outcoming coin request</div>
      <div class="e-ou-space">
      <div class="mt-4 mr-5 ml-5">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Coin Name</th>
			  <th>Price</th>
              <th>Qty</th>
              <th>Total Amount</th>
              <th style="width: 40px">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($data['order_request'] as $index => $order_requests)
				@foreach($order_requests->getRequestedCoinDetails as $orderinfo)
					<tr>
					  <td>1</td>
					  <td>{{ $orderinfo->coin_id }}</td>
					   <td>{{ $orderinfo->initial_price }}</td>
					  <td>{{ $orderinfo->coin_volume }}</td>
					  <td>{{ number_format($orderinfo->initial_price * $orderinfo->coin_volume, 2) }}</td>
					  @if($order_requests->status == 1)
					  <td><span class="badge bg-primary">proof Document Need</span>
              <a href="{{ url('document-update') . '/' .  Crypt::encrypt($order_requests->id) }}" data-toggle="tooltip"
                title="Click to upload document">
                <i class="fa fa-edit"></i>
              </a>
            </td>
					  @elseif($order_requests->status == 2)
					  <td><span class="badge bg-info">Not Approved</span></td>
					 @elseif($order_requests->status == 3)
					  <td><span class="badge bg-success">Approved</span></td>
					  @else
					  <td><span class="badge bg-danger">Rejected</span></td>
					  @endif
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