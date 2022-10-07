@include('layout.email-header')
<div style="padding:0 30px;background:#fff">
  <div style="font-size:12px;line-height:14px;color:#0D0D0D;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:left;">
    <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"> <span style="font-size: 16px; line-height: 39px;">
        <strong>
         Sell order otp Verification
        </strong>
      </span> </p>
    <br>
    <br>
    <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center">Use below sell order OTP to verification process. </p>
    <p style="margin: 16px;font-size: 14px;line-height: 17px;text-align: center"> <span style="font-size: 28px; font-weight: 300;">
        {{ $otp }}
      </span> </p>
    <p style="margin: 0;font-size: 10px;line-height: 17px;text-align: center"> <strong>Note:
      </strong> this OTP will be exipry with in 2 mins. </p>
  </div>
</div>
@include('layout.email-footer')