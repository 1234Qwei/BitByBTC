@include('layout.auth-header', ['pageName' => 'Otp Verification'])
<h5 class="admin_head">OTP Verfication</h5>
<div class="adminlogin_form">
  <form action="{{ route('sign-in') }}" method="post" id="loginForm" autocomplete="off">@csrf
    <input type="hidden" name="step" value="2">
    <input type="hidden" name="user_id" id="user_id" value="{{ $data['user_id'] }}">
     <div class="admin_box mt-5">
      <span id="error" style="color:#faf3f3">{{ $data['message'] }}</span><br>
      <div class="input_login">
        <input type="text" name="otp" id="otp" placeholder="Enter your otp." class="user_box user_box1 password passlog" tabindex="1" >
      </div>
      <span id="timer" style="margin-top: 10px;color: white;"></span><br>
	  
      @if(time() - Session::get('userRegister.time') < EXPIRY_TIME)
        <a href="javascript:void(0)" id="resend" style="display:none;color: #faf3f3;width: 75px;float:right" onclick="resendotp('{{ route('resendotp') }}')" class="btn btn-danger btn-sm">Resend</a>
      @else
			  <a href="javascript:void(0)" id="resend" style="display:block;color: #faf3f3;width: 75px;float:right" onclick="resendotp('{{ route('resendotp') }}')" class="btn btn-danger btn-sm">Resend</a>
      @endif
    {!! app('captcha')->display(['data-size' => 'invisible']) !!}
    <div class="admin_box" style="margin-bottom:0px; margin-top:0px">
      <button type="submit" class="login_but" id="js-submit">Submit</button>
    </div>
    <div class="clearfix"></div>
    <div class="socite"><a href="{{ url('forgot-password') }}">Forgot Password ?</a></div>
    <div class="clearfix"></div>
    <h5 class="alry">Back to login? <a class="for_pass" href="{{ url('sign-in') }}">Sign in</a></h5>
  </form>
</div>
@include('layout.auth-footer')
@if(time() - Session::get('userRegister.time') < EXPIRY_TIME)
  <script>
    timer(`{{ EXPIRY_TIME }}`);
  </script>
@endif
