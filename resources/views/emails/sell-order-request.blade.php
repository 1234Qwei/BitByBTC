@include('layout.email-header')
<div style="padding:0 30px;background:#fff">
    <div style="font-size:12px;line-height:14px;color:#0D0D0D;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:left;">
        <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"> <span style="font-size: 16px; line-height: 39px;">
                <b>
				{{ $orderId }} - Coin Request details
                </b>
            </span> </p>
        <br>
        <table align="center" border="0" style="margin-left: 115px; line-height: 20px;">
            <tbody>
                <tr>
                    <td> </td>
                    <td width="150"> <b>Requested by
                        </b> </td>
                    <td width="10"> </td>
                    <td>{{ $name }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                <tr>
                    <td> </td>
                    <td width="150"> <b>Requested Coin
                        </b> </td>
                    <td width="10"> </td>
                    <td>{{ $coinDetails->coin }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                <tr>
                    <td> </td>
                    <td width="150"> <b>Requested Coin Volume
                        </b> </td>
                    <td width="10"> </td>
                    <td>{{ $coinDetails->coin_volume }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
				<?php
					$coinpriceTotal = $coinDetails->initial_price * $coinDetails->coin_volume; ?>
                <tr>
                    <td> </td>
                    <td width="150"> <b>Requested Coin Price
                        </b> </td>
                    <td width="10"> </td>
                    <td>{{ number_format($coinpriceTotal,2) }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                <tr>
                    <td> </td>
                    <td> <b>Requested Date
                        </b> </td>
                    <td> </td>
                    <td>{{ $requestDate }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@include('layout.email-footer')