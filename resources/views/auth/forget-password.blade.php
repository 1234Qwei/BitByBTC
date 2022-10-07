@include('layout.auth-header', ['pageName' => 'Forgot Password'])
<h5 class="admin_head">You forgot your password? Here you can easily retrieve a new password.</h5>
<div class="adminlogin_form">
	<form action="{{ route('auth.forget-password') }}" method="post" id="forgetPasswordForm">
		@csrf
		<div class="admin_box mt-5">
			<div class="input_login">
				<input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Email Id" class="user_box emaillog email-icon" tabindex="1" />
			</div>
		</div>
		{!! app('captcha')->display(['data-size' => 'invisible']) !!}
		<div class="admin_box" style="margin-bottom:0px; margin-top:0px">
			<button type="submit" class="login_but" id="js-submit">Request new password</button>
		</div>
	</form>
	<div class="clearfix"></div>
	<div class="socite mb-5"><a href="{{ url('sign-in') }}">Back to Login</a></div>
	<div class="clearfix"></div>
</div>
@include('layout.auth-footer')
