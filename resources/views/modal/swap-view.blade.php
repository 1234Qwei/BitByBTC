<form action="{{ route('exchange-update') }}" method="POST" id="swapUpdateForm">
  @csrf
  <input type="hidden" name="id" id="exchange_id" value="{{ $exchange->id }}" />
  <input type="hidden" name="exchange_type" id="exchange_type" value="{{ $exchange->exchange_type }}" />
  <div class="modal-body">
    <dl class="row">
      <dt class="col-sm-4">Exchange type</dt>
      <dd class="col-sm-8">{{ ($exchange->exchange_type == '1') ? 'Deposit' : 'Swap' }}</dd>
       <dt class="col-sm-4">Requested Date</dt>
      <dd class="col-sm-8">{{ $exchange->created_at }}</dd>
      @if($exchange->approved_date)
      <dt class="col-sm-4">Approved Date</dt>
      <dd class="col-sm-8">{{ $exchange->approved_date }}</dd>
      @endif
      <dt class="col-sm-4">Swap From</dt>
      <dd class="col-sm-8"> {{ $exchange->swapFrom->coin }}</dd>
      <dt class="col-sm-4">Swap From Coin</dt>
      <dd class="col-sm-8"> {{ $exchange->swap_from_coin }} {{ $exchange->swapFrom->symbol }}</dd>
      <dt class="col-sm-4">Swap To</dt>
      <dd class="col-sm-8"> {{ $exchange->swapTo->coin }}</dd>
      <dt class="col-sm-4">Swap To Coin</dt>
      <dd class="col-sm-8"> {{ $exchange->swap_to_coin }} {{ $exchange->swapTo->symbol }}</dd>
      @if(Auth::user()->id === 1)
      <dt class="col-sm-4"> Pricing list </dt>
      <dd class="col-sm-8">
      <table class="table table-bordered">
        <thead>
          <th>#</th>
          <th>Coin</th>
          <th>Requested</th>
          <th>Current</th>
        </thead>
        <tbody>
          <tr>
            <th>Swap From</th>
            <td>{{ $exchange->swap_from_coin .' '. $exchange->swapFrom->symbol }}</td>
            <td>{{ '1 '. $exchange->swapFrom->symbol .' ≈ '. $exchange->price_from }} INR</td>
            <td>{{ '1 '. $exchange->swapFrom->symbol .' ≈ '. $calculatedPrice['swapFromPrice'] }} INR</td>
          </tr>
          <tr>
            <th>Swap To</th>
            <td>{{ $exchange->swap_to_coin . ' ' . $exchange->swapTo->symbol }}</td>
            <td>{{ '1 '. $exchange->swapTo->symbol . ' ≈ ' . $exchange->price_to }} INR</td>
            <td>{{ '1 '. $exchange->swapTo->symbol .' ≈ '. $calculatedPrice['swapToPrice'] }} INR</td>
          </tr>
        </tbody>
      </table>  
      </dd>
      @endif
      <dt class="col-sm-4">Status</dt>
        @if(Auth::user()->id === 1)
        <dd class="col-sm-8">
          <div class="col-4 form-group clearfix">
            <div class="icheck-success d-inline">
              <input type="radio" name="status" id="dd_approve" value="1" required @if($exchange->status == 1) checked @endif />
              <label for="dd_approve">Approve</label>
            </div>
          </div>
          <div class="col-4 form-group clearfix">
            <div class="icheck-success d-inline">
              <input type="radio" name="status" id="dd_reject" value="2" required @if($exchange->status == 2) checked @endif/>
              <label for="dd_reject">Reject</label>
            </div>
          </div>
        </dd>
        @else
        <dd class="col-sm-8">
          @if($exchange->status == 0)
          <span class="badge bg-primary">Not Verified</span>
          @elseif($exchange->status == 1)
          <span class="badge bg-success">Approved</span>
          @else
          <span class="badge bg-danger">Rejected</span>
          @endif
        </dd>
        @endif
        @if(Auth::user()->id === 1)
        <dt class="col-sm-4">Approver Comments</dt>
        <dd class="col-sm-8">
          <textarea class="form-control" name="approver_comment" id="dd_approver_comment">{{ $exchange->approver_comment ?? '' }}</textarea>
        </dd>
      </dl>
      @else
      @if($exchange->approver_comment)
      <dt class="col-sm-4">Approver Comments</dt>
      <dd class="col-sm-8" id="dd_approver_comment"> {{ $exchange->approver_comment }}</dd>
      @endif
      @endif 
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-danger swap_update_button" data-dismiss="modal">Close!</button>
    @if(Auth::user()->id === 1)<button type="submit" class="btn btn-primary swap_update_button" @if($exchange->status != 0) disabled="disabled" @endif id="swap_submit_button">Update!</button>@endif
  </div>
</form>