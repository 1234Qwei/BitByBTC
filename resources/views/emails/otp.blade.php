@include('layout.email-header')
<div style="padding:0 30px;background:#fff">
  <div style="font-size:12px;line-height:14px;color:#0D0D0D;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:left;">
    <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"> <span style="font-size: 16px; line-height: 39px;">
        <strong>
          Withdraw details
        </strong>
      </span> </p>
    <br>
    <table align="center" border="0" style="margin-left: 115px; line-height: 20px;">
      <tbody>
        <tr>
          <td> </td>
          <td width="50"> <strong>Address
            </strong> </td>
          <td width="10"> </td>
          <td>{{ $address }} </td>
          <td width="10"> </td>
          <td width="10"> </td>
          <td width="10"> </td>
        </tr>
        <tr>
          <td> </td>
          <td width="50"><strong>Requested Amount</strong></td>
          <td width="10"> </td>
          <td>{{ $amount }} </td>
          <td width="10"> </td>
          <td width="10"> </td>
          <td width="10"> </td>
        </tr>
        <tr>
          <td> </td>
          <td width="50"><strong>Final Amount</strong></td>
          <td width="10"> </td>
          <td>{{ $final_amount }} </td>
          <td width="10"> </td>
          <td width="10"> </td>
          <td width="10"> </td>
        </tr>
        <tr>
          <td> </td>
          <td width="50"><strong>Withdraw Fees</strong></td>
          <td width="10"> </td>
          <td>{{ $fee_amount }} </td>
          <td width="10"> </td>
          <td width="10"> </td>
          <td width="10"> </td>
        </tr>
        @if(!is_null($remarks))
        <tr>
          <td> </td>
          <td> <strong>Remarks
            </strong> </td>
          <td> </td>
          <td>{{ $remarks }} </td>
          <td width="10"> </td>
          <td width="10"> </td>
          <td width="10"> </td>
        </tr>
        @endif
        <tr>
          <td> </td>
          <td> <strong>Date
            </strong> </td>
          <td> </td>
          <td>{{ $created_at }} </td>
          <td width="10"> </td>
          <td width="10"> </td>
          <td width="10"> </td>
        </tr>
      </tbody>
    </table>
    <br>
    <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center">Use below OTP to complete to the transaction. </p>
    <p style="margin: 16px;font-size: 14px;line-height: 17px;text-align: center"> <span style="font-size: 28px; font-weight: 300;">
        {{ $otp }}
      </span> </p>
    <p style="margin: 0;font-size: 10px;line-height: 17px;text-align: center"> <strong>Note:
      </strong> this OTP will be exipry with in 15 mins. </p>
  </div>
</div>
@include('layout.email-footer')