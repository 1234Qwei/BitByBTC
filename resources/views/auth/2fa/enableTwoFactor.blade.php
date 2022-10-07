@include('layout.home-header')
<div class=" b_g">
    <div class="col-md-12 mb-5">
        <form id="msform" method="POST" action="{{ route('g2f-otp-check') }}">
            <!-- progressbar -->
            <ul id="progressbar">
                <li class="active">Step 1</li>
                <li>Step 2</li>
                <li>Step 3</li>
            </ul>
            <!-- fieldsets -->
            <fieldset>
                <h2 class="fs-title">Download App</h2>
                <p>
                    <img src="{{ asset('img/google-2fa.png') }}" width="50%">
                </p>
                <p>Download and install the Google Authentication app</p>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">
                        <img class="play-store-img" src="{{asset('img/play.png')}}" width="100%">
                    </a>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <a href="https://itunes.apple.com/in/app/google-authenticator/id388497605?platform=iphone&preserveScrollPosition=true&platform=iphone#platform/iphone&platform=iphone" target="_blank">
                        <img class="app-store-img" src="{{asset('img/app.png')}}" width="100%" style="margin-bottom: 30px;margin-top: 10px;">
                    </a>
                </div>
                <input type="button" name="next" class="next action-button" value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Scan QR Code</h2>
                <h3 class="fs-subtitle">Your presence on the social network</h3>
                <img src="https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl={{ $image }}" width="200">
                <br />
                <p class="man_code">Manual Code: <span>{{ $secret }}</span></p>
                <p>If you have any problem with scanning the QR code enter this code manually into the APP.</p>
                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                <input type="button" name="next" class="next action-button" value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Backup Key</h2>
                <h3 class="fs-subtitle">Get in your credentials</h3>
                <img src="{{ asset('img/download.png') }}" width="200">
                <p class="description">Please save this Key on paper. This Key will allow you to recover your Google Authentication in case of phone loss.</p>
                <p class="red">Resetting your Google Authentication requires opening a support ticket and takes at least 7 days to process.</p>
                <br />
                <div class="col-md-12 input-field">
                    <div class="contact_title"> Enter your otp from the Google Authendicator App <span>*</span> </div>
                    <input type="number" class="enq-input" id="js-otp" name="js-otp" placeholder="Enter your otp" />
                </div>

                <br />
                <p style="display: none;" id="otp_msg"></p>
                <br />
                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                <button type="button" class="action-button" id="js-2fa-auth">Submit</button>
            </fieldset>
        </form>
    </div>
</div>
@push('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
<script type="text/javascript">
    $(document).on('click', '#js-2fa-auth', function(e) {
        var otp = $('body').find('#js-otp').val();

        if (otp === "") {
            toastr.options = {
                "maxOpened": 1,
                "autoDismiss": true
            };
            toastr.error('Please enter your OTP!');
            return false;
        }

        $.ajax({
            url: "{{ route('g2f-otp-check') }}",
            type: "POST",
            data: {
                "_token": '{{ csrf_token() }}',
                'totp': otp
            }
        }).done(function(response) {
            if (response.status === 200) {
                toastr.success(response.message);
                window.location.href = "{{ url('account') }}";
            } else {
                toastr.error(response.message);
            }
        }).fail(function(jqXhr, status) {});
    });


    var current_fs, next_fs, previous_fs, left, opacity, scale, animating;
    $(document).on('click', ".next", function() {
        if (animating) return false;
        animating = true;
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
        next_fs.show();
        current_fs.animate({
            opacity: 0
        }, {
            step: function(now, mx) {
                scale = 1 - (1 - now) * 0.2;
                left = (now * 50) + "%";
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale(' + scale + ')',
                    'position': 'absolute'
                });
                next_fs.css({
                    'left': left,
                    'opacity': opacity
                });
            },
            duration: 800,
            complete: function() {
                current_fs.hide();
                animating = false;
            },
            easing: 'easeInOutBack'
        });
    });

    $(document).on('click', ".previous", function() {
        if (animating) return false;
        animating = true;
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        previous_fs.show();
        current_fs.animate({
            opacity: 0
        }, {
            step: function(now, mx) {
                scale = 0.8 + (1 - now) * 0.2;
                left = ((1 - now) * 50) + "%";
                opacity = 1 - now;
                current_fs.css({
                    'left': left
                });
                previous_fs.css({
                    'transform': 'scale(' + scale + ')',
                    'opacity': opacity
                });
            },
            duration: 800,
            complete: function() {
                current_fs.hide();
                animating = false;
            },
            easing: 'easeInOutBack'
        });
    });

    $(document).on('click', ".submit", function() {
        return false;
    });
</script>
@endpush
@include('layout.home-footer')
