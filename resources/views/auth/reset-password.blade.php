@include('layout.auth-header', ['pageName' => 'Reset Password'])
<h5 class="admin_head">Reset your account password</h5>
<div class="adminlogin_form">
  <form action="{{ route('auth.reset-password') }}" method="post" id="resetPasswordForm">
    @csrf
    <input type="hidden" name="token" value="{{ $data['token'] }}" />
    <div class="admin_box mt-5">
      <div class="input_login passlog">
        <input type="password" class="user_box" tabindex="0" name="password" id="password" placeholder="Password" />
      </div>
    </div>
    <div class="admin_box">
      <div class="input_login passlog">
        <input type="password" class="user_box" tabindex="1" name="confirm_password" placeholder="Confirm Password">
      </div>
    </div>
    {!! app('captcha')->display(['data-size' => 'invisible']) !!}
    <div class="admin_box" style="margin-bottom:0px; margin-top:0px">
      <button type="submit" class="login_but" id="js-submit">Change password</button>
    </div>
  </form>

  <div class="clearfix"></div>
  <div class="socite mb-5"><a href="{{ url('sign-in') }}">Back to Login</a></div>
  <div class="clearfix"></div>
</div>
@include('layout.auth-footer')