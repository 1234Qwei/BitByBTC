</div>
<!-- Content Wrapper. Contains page content -->
<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <strong>Support:</strong> <a href="mailto:anyswapex@gmail.com" data-toggle="tooltip"
            title="Support team will be reached in 24 Hrs!!!">
            <i class="fas fa-envelope-open-text fa-md"></i> Email
        </a>
    </div>
    <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.anyswap.in">anyswap.in</a>.</strong>
    All rights reserved.
</footer>

<!-- Control Sidebar -->
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="{{ asset('js/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('js/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('js/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('js/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('js/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="{{ asset('js/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- jquery-validation -->
<script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ asset('js/demo.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script src="{{ asset('js/plugins/js-confirm/jquery-confirm.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click", '#js-search', function(e) {
            e.preventDefault();
            var url = '{{ url(' / ') }}?';
            var total = $(".url_params").length;
            $(".url_params").each(function(index) {
                if ($(this).val().trim().length) {
                    if (index === total - 1) {
                        url += $(this).attr('name') + '=' + $(this).val();
                    } else {
                        url += $(this).attr('name') + '=' + $(this).val() + "&";
                    }
                }
            });
            window.location.href = url;
        });
    });
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
        // $('.select2').select2()
        $('.main-sidebar').overlayScrollbars({});
        $('body').overlayScrollbars({});

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

        // Clipboard
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
