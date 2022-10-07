<!DOCTYPE html>
<html lang="en">

<head>
    <title>BitByBTC - Trade</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('img/giobit-fav.png') }}" type="image/png" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}" />
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" /> -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/trade-style.css') }}?{{ date('Y-m-d h:i:s') }}">
    <link rel="stylesheet" href="{{ asset('css/trade.css') }}?{{ date('Y-m-d h:i:s') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/toastr/toastr.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="{{ asset('css/plugins/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/notifIt.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/menu.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="dsb-body dark-theme adv-theme">
    @include('advance_trade/topbar-advanced')
    @include('advance_trade/sidebar-advanced')

    <div class="mobile-menu-cnt"><span onclick="openNav()" class="mobile-menu"><img
                src="{{ asset('img/trade/') }}white-menu.png">{{ trans('app_lang.tab_menu') }}</span></div>

    <!-- Dshboard tabs -->
    <div class="container-fluid dashboard-tabs-cnt">
        <div class="row">

            <!-- Tab panes -->
            <div class="col-xs-12 col-sm-12 tab-content no-padding">

                <!--Exchange tab-->
                <div class="tab-pane container-fluid active" id="exchange">
                    <div class="adv-trade-wrapper">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 card-div adv-trade-status-cnt lg-p-l lg-p-r">
                                <div class="card-div-cnt d-md-flex justify-content-md-between">
                                    <div class="dropdown exc-dd coinDrop">
                                        <button type="button" class="exc-topbar-dd dropdown-toggle">
                                            <span class="exc-dd-heading cur_pair"></span>
                                        </button>
                                        @include('advance_trade/popup-mobile-advanced')
                                    </div>

                                    <div class="exc-data-container">
                                        <div class="exc-data-cnt">
                                            <span class="lgtxt">{{ trans('app_lang.last_price') }}</span>
                                            <span class="lastprice"></span>
                                        </div>
                                        <div class="exc-data-cnt">
                                            <span class="lgtxt">24h {{ trans('app_lang.change') }}</span>
                                            <span class="change"></span>
                                        </div>
                                        <div class="exc-data-cnt">
                                            <span class="lgtxt">24h {{ trans('app_lang.high') }}</span>
                                            <span class="high"></span>
                                        </div>
                                        <div class="exc-data-cnt">
                                            <span class="lgtxt">24h {{ trans('app_lang.low') }}</span>
                                            <span class="low"></span>
                                        </div>
                                        <div class="exc-data-cnt">
                                            <span class="lgtxt">24h {{ trans('app_lang.volume') }} (BTC)</span>
                                            <span class="volume"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 xl-p-r lg-p-l lg-p-r">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 card-div xl-p-l xl-p-r">
                                        <div id="chart_container"></div>
                                    </div>
                                    @include('advance_trade/myhistory-advanced')
                                </div>
                            </div>
                            @include('advance_trade/orderbook-advanced')
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="atrLogin" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">
                    <p>Please <a href="{{ URL::to('/login') }}">login</a> to continue</p>
                </div>

            </div>

        </div>
    </div>
    <div class="clear"></div>
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
                        <li class="fot-bullet"><a href="#"><span>Trading, Deposit & Withdrawal Fees</span></a>
                        </li>
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
    <script>
        var unable_place_order = "{{ trans('app_lang.unable_place_order') }}";
        var stop_price_above = "{{ trans('app_lang.enter_stop_price_above') }}";
        var stop_price_below = "{{ trans('app_lang.enter_stop_price_below') }}";
        var valid_amount = "{{ trans('app_lang.enter_valid_amount') }}";
        var valid_price = "{{ trans('app_lang.enter_valid_price') }}";
        var stop_greater = "{{ trans('app_lang.stop_greater_zero') }}";
        var insufficient_bal = "{{ trans('app_lang.insufficient_bal') }}";
        var valid_stop_price = "{{ trans('app_lang.enter_valid_stop_price') }}";
        var order_placed_success = "{{ trans('app_lang.order_placed') }}";
        var invalid_pair = "{{ trans('app_lang.invalid_pair') }}";
        var cancel_order = "{{ trans('app_lang.want_cancel_order') }}";
        var order_cancel = "{{ trans('app_lang.order_cancelled') }}";
        var error_try = "{{ trans('app_lang.error_try_again') }}";
        var enter_amount_more_than = "{{ trans('app_lang.enter_amount_more_than') }}";
        var enter_price_more_than = "{{ trans('app_lang.enter_price_more_than') }}";
        var no_buy_orders = "{{ trans('app_lang.no_buy_orders') }}";
        var no_sell_orders = "{{ trans('app_lang.no_sell_orders') }}";
        var no_trade_history = "{{ trans('app_lang.no_trade_history') }}";
        var no_open_order_available = "{{ trans('app_lang.no_open_order_available') }}";
        var no_stop_orders = "{{ trans('app_lang.no_stop_orders') }}";
        var no_data_found = "{{ trans('app_lang.no_data_found') }}";

        siteurl = "{{ URL::to('/') }}";
        user_id = "{{ $user ? $user->id : '' }}"
        update_user_id = 0;
        if (user_id != '0') {
            update_user_id = user_id;
        }
        library_path = "{{ asset('js/plugins/charting_library') }}/";
    </script>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/viewportchecker.js') }}"></script> -->
    <!-- <script src="{{ asset('assets/js/script.js') }}"></script> -->
    <script src="{{ asset('js/plugins//notifIt.min.js') }}"></script>
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>

    <script src="{{ asset('js/plugins/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/charting_library/charting_library.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/datafeeds/udf/dist/polyfills.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/datafeeds/udf/dist/bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/socket.io.min.js') }}"></script>
    <script src="{{ asset('js/advance-tradescript.js') }}"></script>
    <script>
        // setTimeout(function() {
        // 	document.getElementById("load").style.display = "none";
        // }, 2000);
        $(document).ready(function() {
            pairData = {};
            getPairdetails('{{ $pairSymbol }}', 8);
            advanceChart('{{ $pairSymbol }}');
            showadvanceBalance();
            getSocketTicker('{{ $pairSymbol }}');
            showMarket();

            $('[data-toggle="tooltip"]').tooltip();
            <?php if (Session::has('success')) { ?>
            var sucess = "{{ Session::get('success') }}";
            notif({
                msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>' + " " + sucess,
                type: "success"
            });
            <?php } ?>
            <?php if (session()->has('error')) { ?>
            var error = "{{ Session::get('error') }}";
            notif({
                msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + " " + error,
                type: "error"
            });
            <?php } ?>
        });
    </script>
    <script>
        $('#settings-profile-link').on('click', function() {
            $('.nav-tabs a[href="#settings-profile"]').click();
        });

        /*Mobile menu script*/

        /* Set the width of the side navigation to 250px and the left margin of the page content to 250px */
        function openNav() {
            document.getElementById("mySidenav").style.width = "150px";
            // document.getElementById("main").style.marginLeft = "150px";
        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            // document.getElementById("main").style.marginLeft = "0";
        }
        $('.nav-link').on('click', function() {
            $('#close-click').click();
        });

        (function($) {

            $(".column").sortable({
                connectWith: ".column",
                handle: ".portlet-header",
                cancel: ".portlet-toggle",

            });

            $(".portlet")
                .addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
                .find(".portlet-header")
                .addClass("ui-widget-header ui-corner-all")
                .prepend("<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");

            $(".portlet-toggle").on("click", function() {
                var icon = $(this);
                icon.toggleClass("ui-icon-minusthick ui-icon-plusthick");
                icon.closest(".portlet").find(".portlet-content").toggle();
            });



            $(window).on("load", function() {
                var width = $(window).width();

                if (width > 1024) {
                    //    big screen
                    $("#openOrdersTable,#balance,.buyOrdersTable,.sellOrdersTable,#myTradeHistory,#myTradeTable,#tradeHistory,#stopOrdersTable,.buytb,.selltb,.tb-265, .tb-289, .tb-299, .tb-357, .tb-1170, .tb-340, .tb-311, .tb-200, .tb-223, .tb-174, .tb-315, .tb-298,.tb-84, .mc-1")
                        .mCustomScrollbar({
                            scrollButtons: {
                                enable: false
                            },

                            scrollbarPosition: 'inside',
                            autoExpandScrollbar: true,
                            theme: 'minimal-dark',
                            axis: "y",
                            setWidth: "auto"
                        });

                } else {
                    $("#openOrdersTable,#balance,.buyOrdersTable,.sellOrdersTable,#myTradeHistory,#myTradeTable,#tradeHistory,#stopOrdersTable,.buytb,.selltb,.tb-265, .tb-289, .tb-299, .tb-357, .tb-1170, .tb-340, .tb-311, .tb-200, .tb-223, .tb-174, .tb-315, .tb-298,.tb-84")
                        .mCustomScrollbar({
                            scrollButtons: {
                                enable: false
                            },

                            scrollbarPosition: 'inside',
                            autoExpandScrollbar: true,
                            theme: 'dark',
                            axis: "x",
                            setWidth: "auto"
                        });

                }
            });
        })(jQuery);

        function setlang(lan) {
            var fiat = lan;
            var link = "{{ URL::to('/setlanguage') }}" + '/' + fiat;
            $.ajax({
                url: link,
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "lan": fiat
                },
                success: function(msg) {
                    location.reload();
                }
            })
        }
    </script>

    <script>
        function chart_wrap() {
            var height = $('.adv-trade-wrapper').height();
            var chart_wrap = (50 * height) / 100;
            /*var chart_wrap = chart_wrap - 8;*/
            chart_wrap = parseInt(chart_wrap) + 'px';
            $("#chart_container iframe").css('height', chart_wrap);
        }

        function orders_wrap() {
            var height = $('.adv-trade-wrapper').height();
            var orders_wrap = (50 * height) / 100;
            var orders_wrap = orders_wrap - 85;
            orders_wrap = parseInt(orders_wrap) + 'px';
            $(".tb-174").css('height', orders_wrap);
        }

        function advtrade_wrap() {
            var height = $('.adv-trade-wrapper').height();
            var advtrade_wrap = (50 * height) / 100;
            var advtrade_wrap = advtrade_wrap - 60;
            advtrade_wrap = parseInt(advtrade_wrap) + 'px';
            $(".tb-223").css('height', advtrade_wrap);
        }

        function buysell_wrap() {
            var height = $('.adv-trade-wrapper').height();
            var buysell_wrap = (50 * height) / 100;
            var buysell_wrap = buysell_wrap - 90;
            var buysell_wrap = buysell_wrap / 2;
            buysell_wrap = parseInt(buysell_wrap) + 'px';
            $(".tb-84").css('height', buysell_wrap);
        }

        function buysell_wrap1() {
            var height = $('.adv-trade-wrapper').height();
            var buysell_wrap1 = (50 * height) / 100;
            var buysell_wrap1 = buysell_wrap1 - 60;
            buysell_wrap1 = parseInt(buysell_wrap1) + 'px';
            $(".tb-200").css('height', buysell_wrap1);
        }

        function limitnew_wrap() {
            var height = $('.adv-trade-wrapper').height();
            var limitnew_wrap = (50 * height) / 100;
            var limitnew_wrap = limitnew_wrap - 74;
            limitnew_wrap = parseInt(limitnew_wrap) + 'px';
            $(".limit_new_class .portlet-content").css('height', limitnew_wrap);
        }

        $(document).ready(function() {
            if ($(window).width() >= 1200) {

                chart_wrap();
                $(window).bind('resize', chart_wrap);

                orders_wrap();
                $(window).bind('resize', orders_wrap);

                advtrade_wrap();
                $(window).bind('resize', advtrade_wrap);

                buysell_wrap();
                $(window).bind('resize', buysell_wrap);

                buysell_wrap1();
                $(window).bind('resize', buysell_wrap1);

                limitnew_wrap();
                $(window).bind('resize', limitnew_wrap);

            }

        });
    </script>
</body>

</html>
