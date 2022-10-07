@include('layout.auth-header', ['pageName' => 'Activation'])
<h5 class="admin_head">Click to activate your account!</h5>
<div class="adminlogin_form">
    <form action="{{ route('activation') }}" method="post" id="activationForm">
        @csrf
        <input type="hidden" name="token" value="{{ $data['token'] }}" />
        {!! app('captcha')->display(['data-size' => 'invisible']) !!}
        <div class="admin_box mt-0 mb-3">
            <button type="submit" class="login_but" id="js-submit">Click to Activate!</button>
        </div>
        <h5 class="alry">Already have an account? <a href="{{ url('login') }}" class="for_pass">Log in</a></h5>
        <h5 class="alry">Not on BitByBTC yet? <a class="for_pass" href="{{ url('sign-up') }}">Sign up</a></h5>
    </form>
</div>
@include('layout.auth-footer')
