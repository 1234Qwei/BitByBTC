@include('layout.home-header')
<div class="homebanner_outer">
    <div class="container">
        <h2>Buy & Sell Crypto in minutes</h2>
        <div class="clear"></div>
        <h5>Join the world's largest crypto exchange</h5>
        <div class="clear"></div>
        @if (Auth::check())
            <a href="{{ url('p2p-exchange') }}">
                <div class="acc-ou-1-1 sebut_reg">Trade</div>
            </a>
        @else
            <a href="{{ url('sign-up') }}">
                <div class="acc-ou-1-1 sebut_reg">Register Now</div>
            </a>
        @endif
    </div>
</div>
<div class="clear"></div>
<!--homeslider_outer-start--->
<div class="homeslider_outer">
    <div class="container">
        <!--hometestslider start-->
        <div id="hometestslider" class="owl-carousel">
            <div class="item_new"><img src="{{ asset('img/cover/1.jpg') }}" alt="BitByBTC.com" /></div>
            <div class="item_new"><img src="{{ asset('img/cover/2.jpg') }}" alt="BitByBTC.com" /></div>
            <div class="item_new"><img src="{{ asset('img/cover/3.jpg') }}" alt="BitByBTC.com" /></div>
            <div class="item_new"><img src="{{ asset('img/cover/4.jpg') }}" alt="BitByBTC.com" /></div>
            <div class="item_new"><img src="{{ asset('img/cover/1.jpg') }}" alt="BitByBTC.com" /></div>
            <div class="item_new"><img src="{{ asset('img/cover/2.jpg') }}" alt="BitByBTC.com" /></div>
            <div class="item_new"><img src="{{ asset('img/cover/3.jpg') }}" alt="BitByBTC.com" /></div>
            <div class="item_new"><img src="{{ asset('img/cover/4.jpg') }}" alt="BitByBTC.com" /></div>
        </div>
        <!--hometestslider start-->
    </div>
</div>
<!--homeslider_outer-->
<div class="clear"></div>
<!--body-start--->
<div class="header-out-1">
    <div class="container">
        <div id="js-pricing-chart">
            @php $pairs = ["BTT/USDT", "BNB/BUSD", "ETH/USDT"]; @endphp
            @foreach ($pairs as $pair)
                @php
                    $selector = Str::lower(str_replace('/', '', $pair));
                    $splitPair = explode('/', $pair);
                @endphp
                <div class="col-md-4">
                    <div class="contapart_outer">
                        <div class="cont_valout">
                            <h4><a href="{{ url('p2p-exchange') . '/' . $splitPair[0] . '_' . $splitPair[1] }}">
                                    {{ $pair }}</a></h4>
                            <h5><span id="ws-{{ $selector }}-lprice">&#8377;380.64</span> <span
                                    id="ws-{{ $selector }}-cprice" style="font-size:12px"></span></h5>
                        </div> <img class="cont_imgwave" src="{{ asset('img/home-wave.png') }}" />
                        <div class="clearfix"></div>
                        <p><span id="ws-{{ $selector }}-per-price">-8.52</span>% Volume: <span
                                id="ws-{{ $selector }}-tvolume">609,714,789.67</span> {{ $splitPair[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="clear"></div>
        <div class="col-md-12">
            <h2 class="market_headd">Market Trend</h2>
            <div class="homepair_out" style="float:left; width:100%; margin-top:30px;">
                <ul class="nav nav-tabs favul">
                    <li class="nav-item active">
                        <a class="nav-link" data-toggle="tab" href="#btc">BTC</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#eth">ETH</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#trx">TRX</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#favorites">Favorites</a>
                    </li>
                </ul>

                <form action="" method="post" name="f1" id="f1" class="sear_f1">
                    <input type="search" class="sear_text" placeholder="Search" />
                    <input type="submit" class="sear_box" value="" />
                </form>
                <div class="clearfix"></div>
                <!-- Tab panes -->
                <div class="tab-content">
                    @php $marketPairs = ['ETH/BTC', 'LTC/BTC', 'BTC/USDT', 'BTC/BUSD']; @endphp
                    <div class="tab-pane container active" id="btc">
                        <div class="table-responsive">
                            <table class="table table-striped" cellspacing="0" cellpadding="4px" border="0">
                                <thead>
                                    <th></th>
                                    <th>Pair<img src=" {{ asset('img/pair-ic.png') }}" /></th>
                                    <th>Last Price<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h High<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h Low<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h Volume<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>Marketcap<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                </thead>
                                <tbody id="js-btc-market">
                                    <tr id='js-loader'>
                                        <td align="center" colspan="7">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane container" id="eth">
                        <div class="table-responsive">
                            <table class="table table-striped" cellspacing="0" cellpadding="4px" border="0">
                                <thead>
                                    <th></th>
                                    <th>Pair<img src=" {{ asset('img/pair-ic.png') }}" /></th>
                                    <th>Last Price<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h High<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h Low<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h Volume<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>Marketcap<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                </thead>
                                <tbody id="js-btc-market">
                                    <tr id='js-loader'>
                                        <td align="center" colspan="7">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane container" id="trx">
                        <div class="table-responsive">
                            <table class="table table-striped" cellspacing="0" cellpadding="4px" border="0">
                                <thead>
                                    <th></th>
                                    <th>Pair<img src=" {{ asset('img/pair-ic.png') }}" /></th>
                                    <th>Last Price<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h High<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h Low<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h Volume<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>Marketcap<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                </thead>
                                <tbody id="js-btc-market">
                                    <tr id='js-loader'>
                                        <td align="center" colspan="7">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane container" id="favorites">
                        <div class="table-responsive">
                            <table class="table table-striped" cellspacing="0" cellpadding="4px" border="0">
                                <thead>
                                    <th></th>
                                    <th>Pair<img src=" {{ asset('img/pair-ic.png') }}" /></th>
                                    <th>Last Price<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h High<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h Low<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>24h Volume<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                    <th>Marketcap<img src="{{ asset('img/pair-ic.png') }}" /></th>
                                </thead>
                                <tbody id="js-btc-market">
                                    <tr id='js-loader'>
                                        <td align="center" colspan="7">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="text-center" style="float:left; width:100%; margin-top:20px; margin-bottom:20px;">
                <a href="#">
                    <div class="acc-ou-1-1 sebut_reg">View More Markets &nbsp;<img
                            src="{{ asset('img/coins/arr.png') }}" /></div>
                </a>
            </div>
        </div>
    </div>
</div>
<!--body-end--->
<div class="clear"></div>

<div class="services pd patdet_outew">
    <div class="container">
        <h2 class="market_headd title">Manage Your Crypto Assets With Us!</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="square"><img src="{{ asset('img/coins/i1.png') }}" /></div>
                <div class="serv">
                    <h5>Strong Security</h5>
                    <h3>Features</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="serv">
                    <div class="square"><img src="{{ asset('img/coins/i2.png') }}" /></div>
                    <h5>24/7 Customer</h5>
                    <h3>Support</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="square"><img src="{{ asset('img/coins/i3.png') }}" /></div>
                <div class="serv">
                    <h5>Easy User</h5>
                    <h3>Interface</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="square"><img src="{{ asset('img/coins/i4.png') }}" /></div>
                <div class="serv">
                    <h5>Fees Starting</h5>
                    <h3>at 0%</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="square"><img src="{{ asset('img/coins/i5.png') }}" /></div>
                <div class="serv">
                    <h5>Safety</h5>
                    <h3>of Funds</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="square"><img src="{{ asset('img/coins/i6.png') }}" /></div>
                <div class="serv">
                    <h5>Instant Deposits</h5>
                    <h3>& Withdrawals</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>
<div class="patdet_outew" style="background:#fff;">
    <div class="container">
        <h2 class="market_headd statrthead">Start trading now</h2>
        <div class="clear"></div>
        <h5 class="market_headd1">Join the world's largest crypto exchange</h5>
        <div class="text-center" style="float:left; width:100%; margin-top:20px; margin-bottom:20px;">
            <a href="{{ url('sign-up') }}">
                <div class="acc-ou-1-1 sebut_reg" style="margin-right:10px;">Register Now</div>
            </a>
            <a href="#">
                <div class="acc-ou-2-2 sebut_reg">Trade Now</div>
            </a>
        </div>
    </div>
</div>
@include('layout.home-footer')
