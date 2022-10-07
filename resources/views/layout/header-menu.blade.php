 <div class="header-out">
     <div class="container container-1400">
         <div class="col-md-2">
             <a href="{{ url('/') }}">
                 <div class="head-logo"> <img src="{{ asset('img/logo-giobit.png') }}" style="width: 129px;"
                         alt="BitByBTC Logo" /> </div>
             </a>
         </div>
         <!--menu-->
         <div class="@if (Auth::check()) col-md-8 @else col-md-7 @endif">
             <div class="menu-out">
                 <div id='cssmenu'>
                     <ul >
                         @if (Auth::check())
                             <li 
                                 class="first {{ Request::is('account') || Request::is('profile-verification') ? 'active' : '' }}">
                                 <a href="{{ url('account') }}">User Centre</a>
                             </li>
                             <li class="{{ Request::is('p2p-exchange') ? 'active' : '' }}"><a
                                     href="{{ url('/p2p-exchange') }}">Exchange </a></li>
                             <li class="{{ Request::is('deposit') ? 'active' : '' }}"><a
                                     href="{{ url('deposit') }}">Deposit History </a></li>
                             <li class="{{ Request::is('balance') ? 'active' : '' }}"><a
                                     href="{{ url('balance') }}">Wallets</a></li>
                             <li class="{{ Request::is('list-sellorder') ? 'active' : '' }}"><a
                                     href="{{ url('list-sellorder') }}">List Order</a></li>
                             <li class="{{ Request::is('outcoming-coin-request') ? 'active' : '' }}"><a
                                     href="{{ url('outcoming-coin-request') }}">Outcoming coin request</a></li>
                             <li class="{{ Request::is('incoming-coin-request') ? 'active' : '' }}"><a
                                     href="{{ url('incoming-coin-request') }}">Incoming coin request</a></li>

                             <li class="{{ Request::is('referrals') ? 'active' : '' }}"><a
                                     href="javascript:://">Referrals</a></li>
                             <li class="{{ Request::is('support') ? 'active' : '' }}"><a
                                     href="javascript:://">Support </a></li>
                             <li class="last {{ Request::is('banks') ? 'active' : '' }}"><a
                                     href="{{ url('banks') }}">Bank Details</a></li>
                                     <!-- <li class=" {{ Request::is('stacking-contract') ? 'active' : '' }}"><a
                                     href="{{ url('stacking-contract') }}">Stacking Contract</a></li> -->
                                     <li class=" {{ Request::is('banks') ? 'active' : '' }}"><a
                                     href="{{ url('contract') }}">Stacking List</a></li>
                                     <li class=" {{ Request::is('banks') ? 'active' : '' }}"><a
                                     href="{{ url('bonus') }}">Bonus</a></li>
                         @else
                             <li class="{{ Request::is('trade/*') ? 'active' : '' }}"><a
                                     href="{{ url('trade') . '/BNB_USDT' }}">Trade </a></li>
                             <li class="{{ Request::is('exchange') ? 'active' : '' }}"><a
                                     href="javascript:://">Exchange </a></li>
                             <li class="{{ Request::is('API') ? 'active' : '' }}"><a href="javascript:://">API </a>
                             </li>
                         @endif
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
                                 <div class="acc-ou-2-2">Login</div>
                             </a>
                         </div>
                         <div class="mail_id_img2 hvr-grow-rotate">
                             <a href="{{ url('sign-up') }}">
                                 <div class="acc-ou-1-1">Register</div>
                             </a>
                         </div>
                     @endif
                 </div>
             </div>
         </div>
     </div>
 </div>
