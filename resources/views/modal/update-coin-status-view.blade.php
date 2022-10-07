<form action="{{ route('update-coin-status') }}" method="POST" id="depositUpdateForm" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="coin_request_id" id="coin_request_id" value="{{ $incoming_request_id }}" />
    <div class="modal-body">
        <div class="row">
            <dt class="col-sm-4">Exchange type</dt>
            <dd class="col-sm-8">Buy</dd>	
			<br><br>
            <dt class="col-sm-4">Buyer Name</dt>
            <dd class="col-sm-8">{{ $incoming_coin_request[0]->getUserDetails->first_name }}</dd>	
			<br><br>
            <dt class="col-sm-4">Coin Name</dt>
            <dd class="col-sm-8">{{ $incoming_coin_request[0]->getRequestedCoinDetailsOne->coin_id }}</dd>	
			<br><br>
            <dt class="col-sm-4">Qty</dt>
            <dd class="col-sm-8">{{ $incoming_coin_request[0]->getRequestedCoinDetailsOne->coin_volume }}</dd>	
			<br><br>
            <dt class="col-sm-4">Single coin price</dt>
            <dd class="col-sm-8">{{ $incoming_coin_request[0]->getRequestedCoinDetailsOne->initial_price }}</dd>	
			<br><br>
            <dt class="col-sm-4">Total Amount</dt>
            <dd class="col-sm-8">{{ number_format($incoming_coin_request[0]->getRequestedCoinDetailsOne->initial_price * $incoming_coin_request[0]->getRequestedCoinDetailsOne->coin_volume, 2) }}</dd>				
			<br><br>
            <dt class="col-sm-4">Transaction Proof</dt>
			<dd class="col-sm-8">{{ $incoming_coin_request[0]->transaction_proof }}</dd>	
			<br><br>	
			<?php
			
				if($incoming_coin_request[0]->status==3 || $incoming_coin_request[0]->status==4){
					$status = 1;
				}else{
					$status = 0;
				}
			?>
			<br><br>
            <dt class="col-sm-4">Status</dt>
			
                <dd class="col-sm-4">
                    <div class="col-4 form-group clearfix">
					@if($status==1)
						@if($incoming_coin_request[0]->status==3)
							<span class="btn btn-success">Approved</span>
						@else
							<span class="btn btn-danger">Rejected</span>
						@endif
					
					@else
						<select class="form-control" name="status">
						
							<option value="3">Approve</option>
							
							<option value="4">Reject</option>
						</select>						
					@endif
                    </div>
                </dd>	
			<br><br>				
		</div>
	</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger deposit_button" data-dismiss="modal">Close!</button>
		@if($status==0)
			<button type="submit" class="btn btn-primary deposit_button" id="deposit_submit">Update!</button>
		@endif
    </div>
</form>
