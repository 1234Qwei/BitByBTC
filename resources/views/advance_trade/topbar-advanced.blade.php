 <!--header-start--->
 @include('layout.header-menu')
 <!--header-end--->

 <div class="clear"></div>
 <div class="pos_abs">
 	<?php if (Session::has('tmaitb_user_id')) { ?>

 	<?php } ?>

 	<div class="dropdown exc-dd dropdown coinDrop"><img style="width: 25px; height: 25px;" src="{{asset('/').('img/trade/')}}bitcoin-icon.png">
 		<button type="button" class="exc-topbar-dd dropdown-toggle">
 			<span class="exc-dd-heading cur_pair"></span>
 		</button>
 		@include('advance_trade/popup-advanced')


 	</div>
 	<div class="exc-data-cnt">
 		<span class="lgtxt ">{{trans('app_lang.last_price') }}</span>
 		<span class="lastprice"></span>
 	</div>
 	<div class="exc-data-cnt">
 		<span class="lgtxt">24h {{trans('app_lang.change') }}</span>
 		<span class="change"></span>
 	</div>
 	<div class="exc-data-cnt">
 		<span class="lgtxt">24h {{trans('app_lang.high') }}</span>
 		<span class="high"></span>
 	</div>
 	<div class="exc-data-cnt">
 		<span class="lgtxt">24h {{trans('app_lang.low') }}</span>
 		<span class="low"></span>
 	</div>
 	<div class="exc-data-cnt">
 		<span class="lgtxt">24h {{trans('app_lang.volume') }} (<span class="from_cur"></span>)</span>
 		<span class="volume"></span>
 	</div>
 </div>