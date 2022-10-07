@include('layout.home-header')
@php use App\Models\User; @endphp
<style>
dl {
  margin-top: 0;
  margin-bottom: 21px;
}
dt, dd {
  line-height: 1.42857143;
}
dt {
  font-weight: bold;
}
dd {
  margin-left: 0;
}
</style>
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title">Advertiser Information</div>
      <div class="e-ou-space">
        <div class="p-5">
        <form action="{{ route('update-coin-status') }}" method="POST" enctype="multipart/form-data">@csrf
        <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
            <div class="package-title">Advertiser Information</div>	
            <br><br><br><br>
                <dt class="col-sm-4">Seller Name</dt>
                <dd class="col-sm-8">{{ $data['userInfo']['first_name'] }} {{ $data['userInfo']['last_name'] }}<br><br></dd>	
                <dt class="col-sm-4">Joined Date</dt>
                <dd class="col-sm-8">{{ date('Y-m-d',strtotime($data['userInfo']['created_at'])) }}<br><br></dd>	
                <dt class="col-sm-4">Mobile</dt>
                <dd class="col-sm-8">Verified<br><br></dd>	
                <dt class="col-sm-4">E-mail</dt>
                <dd class="col-sm-8">Verified<br><br></dd>	
                <dt class="col-sm-4">Address</dt>
                <dd class="col-sm-8">Verified<br><br></dd>		
            </div>
            
            <div class="col-sm-6">
            <?php $userSellOrderCount = User::getUserSellOrderCount($data['userInfo']['id']) ?>
            <div class="package-title">Trading Information</div>
            <br><br><br><br>	  
            <dt class="col-sm-8">Total Trade</dt>
                <dd class="col-sm-4">{{ $userSellOrderCount['totalCount'] }}<br><br></dd>	
                <dt class="col-sm-8">Last 30 days Trade</dt>
                <dd class="col-sm-4">{{ $userSellOrderCount['lastThirtydays'] }}<br><br></dd>	
                <dt class="col-sm-8">30d Completion Rate</dt>
                <dd class="col-sm-4">{{ $userSellOrderCount['completionRate'] }}<br><br></dd>	
                <dt class="col-sm-8">Avg. Release Time</dt>
                <dd class="col-sm-4">4.89Minute(s)<br><br></dd>
            </div>				
		</div>
        <div class="row">
			
		</div>
	</div>
    <div class="modal-footer">
        <a href="{{ url('/p2p-exchange') }}" class="btn btn-danger">Back</a>
    </div>
</form>

        </div>
      </div>
    </div>
  </div>
</div>
@push('scripts')

@endpush
@include('layout.home-footer')