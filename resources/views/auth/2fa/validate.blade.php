@include('layout.auth-header', ['pageName' => 'Log in'])
<h5 class="admin_head">Use your "BitByBTC" Account</h5>
<div class="adminlogin_form mb-2">
    <form class="form-horizontal" role="form" method="POST" action="{{ route('sign-validate') }}" id="js-otpForm">
        {!! csrf_field() !!}
        <div class="admin_box mt-5">
            <div class="input_login passlog">
                <input type="number" class="user_box user_box1 password" name="authOtp" id="js-otp"
                    placeholder="Please enter your OTP" />
            </div>
        </div>
        <div class="admin_box" style="margin-bottom:0px; margin-top:0px">
            <button type="submit" id="js-otp-submit" class="btn btn-primary">
                Validate
            </button>
        </div>
    </form>
</div>
@push('scripts')
    <script>
        $(document).on('keyup', '#js-otp', function() {
            let otp = $(this).val().length;
            if (otp === 6) {
                $('body').find('#js-otp-submit').attr('disabled', true).text('Loading...');
                $('body').find('#js-otpForm').submit();
            }
            return false;
        });

        var hasError = "{{ $errors->has('authOtp') }}";
        $(document).ready(function() {
            if (hasError) {
                toastr.error("{{ $errors->first('authOtp') }}");
                $('body').find('#js-otp-submit').attr('disabled', false).text('Validate');
            }
        });
    </script>
@endpush
@include('layout.auth-footer')
