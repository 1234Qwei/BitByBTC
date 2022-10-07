@include('layout.auth-header', ['pageName' => 'Sign up'])
<h5 class="admin_head">Create a free account</h5>
<div class="adminlogin_form">
    <form action="{{ route('sign-up') }}" method="post" id="registerForm" autocomplete="off">
        <input type="hidden" name="referred_by" value="{{ request()->get('ref') ?? '' }}" />
        @csrf
        <div class="admin_box mt-5">
            <div class="input_login">
                <input name="email" value="{{ old('email') }}" id="email" placeholder="Email Id"
                    class="user_box " required autocomplete="off" type="email">
                @error('email')
                    <span id="email-error" class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="admin_box">
            <div class="input_login">
                <input name="mobile" value="{{ old('mobile') }}" id="mobile" placeholder="mobile"
                    class="user_box " required autocomplete="off" type="number">
                @error('mobile')
                    <span id="mobile-error" class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="admin_box">
            <div class="input_login">
                <input name="password" id="password" placeholder="Password."
                    class="user_box user_box1 password  passlog" required type="password"> <i
                    class="fa fa-eye-slash js-password-icon"></i>
            </div>
        </div>
        <div class="admin_box">
            <div class="input_login">
                <input name="confirm_password" id="confirm_password" placeholder="Confirm Password."
                    class="user_box user_box1 password  passlog" required type="password"> <i
                    class="fa fa-eye-slash js-password-icon"></i>
            </div>
        </div>
        <div class="socite1">
            <input type="checkbox" name="terms" id="terms" class="ch_box1" /><label for="terms">I'have read and
                agreed <a href="{{ url('privacy-policy') }}" target="_blank">Privacy Policy</a> with <a
                    href="terms-conditions.html" target="_blank">Terms and
                    Conditions</a></label>
        </div>
        <div class="clearfix"></div>
        {!! app('captcha')->display(['data-size' => 'invisible']) !!}
        <div class="admin_box" style="margin-bottom:0px;">
            <button type="submit" class="login_but" id="js-submit">Create Account</button>
        </div>
    </form>
    <h5 class="alry">Already Registered? <a class="for_pass" href="{{ url('sign-in') }}">LOGIN</a></h5>
    <div class="clearfix"></div>
</div>
@include('layout.auth-footer')
