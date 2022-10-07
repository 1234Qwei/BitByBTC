@include('layout.home-header')
@include('modal.otp')
<style>

h1 {
  font-weight: normal;
  letter-spacing: .125rem;
  text-transform: uppercase;
}

li {
  display: inline-block;
  font-size: 1.5em;
  list-style-type: none;
  padding: 1em;
  text-transform: uppercase;
}

li span {
  display: block;
  font-size: 4.5rem;
}

.emoji {
  display: none;
  padding: 1rem;
}

.emoji span {
  font-size: 4rem;
  padding: 0 .5rem;
}

@media all and (max-width: 768px) {
  h1 {
    font-size: calc(1.5rem * var(--smaller));
  }
  
  li {
    font-size: calc(1.125rem * var(--smaller));
  }
  
  li span {
    font-size: calc(3.375rem * var(--smaller));
  }
}
</style>
<div class="header-out-1">
  <div class="container">
    <div class="package-2">
      <div class="package-title">Upload Document Proof</div>
      <div class="e-ou-space">
        <div class="p-5" style="margin-left:20%">
		    <input type="hidden" id="mytimer" value="{{ Session::get('payTimer') }}">
          <form id="sellUpdateForm" method="post" action="{{ url('/upload-document-proof') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="order_request_id" value="{{ Session::get('order_request_id') }}">
            <div class="row">
              <div class="form-group col-md-12">
					  <div id="countdown">
						<ul>
						  <li><span id="minutes"></span>Minutes</li>
						  <li><span id="seconds"></span>Seconds</li>
						</ul>
					  </div>
              </div>
            </div>
			  <div class="row">
			  <label>Upload Document :<span class="text-danger"></span></label></br></br>
				  <div class="form-group col-md-12">
					 
					<input type="file" name="document_proof" class="form-control">
				  
				  </div>
			  </div>
            <div class="row">
              <div class="col-md-4 col-md-offset-8 pull-right">
                <button type="submit" class="btn-md btn btn-warning js-progress" id="js-update">Upload Document</button>
                <a href="{{ url('list-sellorder') }}" class="btn-md btn btn-danger js-progress">Back</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script src="{{ asset('js/plugins/timer.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#sellUpdateForm').on('submit', function(e){
          $('#sellUpdateForm').submit();
           // e.preventDefault();
           // $("#otpVerification").modal("toggle");
        });
    }); 
$(window).on('load', function() {
	timer();
 });
	
</script>

@endpush

@include('layout.home-footer')