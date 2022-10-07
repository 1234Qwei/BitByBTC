<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BitByBTC</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('img/giobit-fav.png') }}" type="image/png" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/menu.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/owl.carousel.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="background:#fff;">
    <div class="header-out">
        <div class="container" style="margin-left: 0px;">
            <div class="col-md-2">
                <a href="{{ url('/') }}">
                    <div class="head-logo"> <img src="{{ asset('img/logo-giobit.png') }}" alt="BitByBTC Logo" />
                    </div>
                </a>
            </div>
            <!--menu-->
            <div class="@if (Auth::check()) col-md-8 @else col-md-7 @endif">
                <div class="menu-out">
                    <div id='cssmenu'>
                        <ul>

                            <li class="{{ Request::is('trade/*') ? 'active' : '' }}"><a
                                    href="{{ url('trade') . '/BNB_USDT' }}">Trade </a></li>
                            <li class="{{ Request::is('exchange') ? 'active' : '' }}"><a
                                    href="javascript:://">Exchange </a></li>
                            <li class="{{ Request::is('API') ? 'active' : '' }}"><a href="javascript:://">API </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <!--menu-->
            <div class="@if (Auth::check()) col-md-2 @else col-md-3 @endif">
                <div class="head-icons">
                    <div class="soci-out">
                        @if (Auth::check())
                            <div class="mail_id_img2 hvr-grow-rotate">
                                <a href="javascript:://"> <img src="{{ asset('img/anonuncement.png') }}"> </a>
                            </div>
                            <div class="mail_id_img2 hvr-grow-rotate">
                                <a href="javascript:://"> <img src="{{ asset('img/notify_bell.png') }}"> </a>
                            </div>
                            <div class="mail_id_img2 hvr-grow-rotate">
                                <a href="{{ url('logout') }}"> <img src="{{ asset('img/account.png') }}"> </a>
                            </div>
                        @else
                            <div class="mail_id_img2 hvr-grow-rotate">
                                <a href="{{ url('sign-in') }}">
                                    <div class="acc-ou-2-2"> <i class="fa fa-sign-in"></i>Login</div>
                                </a>
                            </div>
                            <div class="mail_id_img2 hvr-grow-rotate">
                                <a href="{{ url('sign-up') }}">
                                    <div class="acc-ou-1-1"><i class="fa fa-user-plus" aria-hidden="true"></i> Register
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>


    <!----------------->
    <section>
        <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white css-vurnk col-md-2">

            <div class="list-group list-group-flush border-bottom scrollarea css-vurnku">
                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Coin
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> User Centre
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Trade
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Exchange
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Deposit History
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Wallets
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> API
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Funds
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Referrals
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Support
                </a>

                <a href="#" class="list-group-item list-group-item-action py-3 lh-tight" aria-current="true">
                    <i class="fa fa-sign-in"></i> Bank Details
                </a>


            </div>
        </div>

    </section>
    @include('layout.home-footer')
