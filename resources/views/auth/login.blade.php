@include('layout.auth-header', ['pageName' => 'Log in'])
<h5 class="admin_head">Use your "Bitbybtc" Account</h5>
<div class="adminlogin_form">
  <form action="{{ route('sign-in') }}" method="post" id="loginForm" autocomplete="off">@csrf
    <input type="hidden" name="step" value="1">
    <div class="admin_box mt-5">
      <div class="input_login">
        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Email ID" class="user_box emaillog email-icon" tabindex="1" > 
      </div>
    </div>
    <div class="admin_box">
      <div class="input_login">
        <input type="password" name="password" id="password" placeholder="Enter your password." class="user_box user_box1 password passlog" tabindex="1" > <i class="fa fa-eye-slash js-password-icon"></i>
      </div>
    </div>
    {!! app('captcha')->display(['data-size' => 'invisible']) !!}
    <div class="admin_box" style="margin-bottom:0px; margin-top:0px">
      <button type="submit" class="login_but" id="js-submit">Login</button>
    </div>
    <div class="clearfix"></div>
    <div class="socite"><a href="{{ url('forgot-password') }}">Forgot Password ?</a></div>
    <div class="clearfix"></div>
    <h5 class="alry">Not on BITBYBTC yet? <a class="for_pass" href="{{ url('sign-up') }}">Sign up</a></h5>
  </form>
</div>
@include('layout.auth-footer')
