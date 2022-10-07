<div class="footer-bg">
    <div class="container container-1500">
        <div class="col-md-3">
            <div class="fot-heading"> About
                <br />
            </div>
            <ul class="ul_footer-1">
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="#">Referral Program</a></li>
                <li><a href="#">blog</a></li>
                <li><a href="#">Careers</a></li>
            </ul>
        </div>
        <div class="col-md-3">
            <div class="fot-heading"> Legal
                <br />
            </div>
            <div class="fot-links allmenu">
                <ul>
                    <li class="fot-bullet"><a href="terms-conditions.html"><span>Terms of use</span></a></li>
                    <li class="fot-bullet"><a href="privacy-policy.html"><span>Privacy Policy</span></a></li>
                    <li class="fot-bullet"><a href="api.html"><span>API</span></a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <div class="fot-heading"> Support
                <br />
            </div>
            <div class="fot-links allmenu">
                <ul>
                    <li class="fot-bullet"><a href="#"><span>Help Center</span></a></li>
                    <li class="fot-bullet"><a href="#"><span>Corporate Account</span></a></li>
                    <li class="fot-bullet"><a href="#"><span>List your Token</span></a></li>
                    <li class="fot-bullet"><a href="#"><span>Partnerships & Enquiries</span></a></li>
                    <li class="fot-bullet"><a href="#"><span>Media Assets</span></a></li>
                    <li class="fot-bullet"><a href="#"><span>Trading, Deposit & Withdrawal Fees</span></a></li>
                    <li class="fot-bullet"><a href="#"><span>Security</span></a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <div class="fot-out21">
                <div class="fot-heading"> SOCIAL MEDIA
                    <br />
                </div>
                <ul class="ul_footer-1">
                    <li><img src="{{ asset('img/telegram.png') }}" /><a href="#">Telegram</a></li>
                    <li> <img src="{{ asset('img/twitter.png') }}" /><a href="#">Twitter</a></li>
                    <li> <img src="{{ asset('img/facebook.png') }}" /><a href="#">Facebook</a></li>
                    <li><img src="{{ asset('img/youtube.png') }}" /><a href="#">Youtube</a></li>
                    <li> <img src="{{ asset('img/linkedIn.png') }}" /><a href="#">LinkedIn</a></li>
                    <li> <img src="{{ asset('img/instagram.png') }}" /><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<div class="fot-pattern">
    <div class="container ">
        <div class="copyright-text-1"> Copyright &copy; {{ date('Y') }} BitByBTC . All rights reserved. </div>
    </div>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/ws.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.js') }}"></script>
<script src="{{ asset('js/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/demo.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery-confirm.js') }}"></script>
<script src="{{ asset('js/popup.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script src="{{ asset('js/plugins/js-confirm/jquery-confirm.min.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/jsvaq6zmse864jif3zezeyqif00kidx9h10npywbe9lwb990/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
        toolbar_mode: 'floating',
    });

    @if (Session::has('success'))
        toastr.success(`{{ Session::get('success') }}`)
    @endif

    @if (Session::has('error'))
        toastr.error(`{{ Session::get('error') }}`)
    @endif


    @if (Request::is('exchange') || Request::is('withdraw/*'))
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            width: 120,
            height: 120
        });

        function makeQrcode(e) {
            qrcode.makeCode(e.attr("data-url"));
        }
        $(document).ready(function() {
            jQuery("[data-toggle='popover']").popover(
                options = {
                    content: jQuery("#qrcode"),
                    html: true
                }
            );

            jQuery("[data-toggle='popover']").on("show.bs.popover", function(e) {
                makeQrcode(jQuery(this));
                jQuery("#qrcode").show();
            });

        });
    @endif
    $(document).ready(function() {
        $(".select2").select2();
        $("#hometestslider").owlCarousel({
            navigation: true,
            autoHeight: true,
            autoPlay: true,
            pagination: false,
            items: 4,
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [979, 2],
            itemsTablet: [768, 2],
            itemsTabletSmall: false,
            itemsMobile: [480, 1]
        });

        function setTooltip(message, e) {
            e.tooltip('hide')
                .attr('data-original-title', message)
                .tooltip('show');
        }

        function hideTooltip(e) {
            setTimeout(function() {
                e.tooltip('hide');
            }, 1000);
        }

        var clipboard = new ClipboardJS('.cpy-btn');

        clipboard.on('success', function(e) {
            var element = $(e.trigger);
            setTooltip('Copied!', element);
            hideTooltip(element);
        });

        clipboard.on('error', function(e) {
            var element = $(e.trigger);
            setTooltip('Failed!', element);
            hideTooltip(element);
        });

        @if (Session::has('message'))
            toastr.success(`{{ Session::get('message') }}`)
        @endif

        @if (Session::has('error'))
            toastr.error(`{{ Session::get('error') }}`)
        @endif

        @if (Request::is('exchange') && Request::get('depositCoin'))
            var seletedCoin = "{{ request()->get('depositCoin') }}";
            $('body').find('#deposit_currency').val(seletedCoin).change();
        @endif
    });
</script>
@stack('scripts')
</body>

</html>
