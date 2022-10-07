@include('layout.email-header')
<div style="padding:0 30px;background:#fff">
    <div style="font-size:12px;line-height:14px;color:#0D0D0D;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:left;">
        <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"> <span style="font-size: 16px; line-height: 39px;">
                <b>
                    Deposit Request details
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
                    <td>{{ $user }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                <tr>
                    <td> </td>
                    <td width="150"> <b>Coin
                        </b> </td>
                    <td width="10"> </td>
                    <td>{{ $coin }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                <tr>
                    <td> </td>
                    <td> <b>Requested Date
                        </b> </td>
                    <td> </td>
                    <td>{{ $created_at }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                <tr>
                    <td> </td>
                    <td> <b>Status
                        </b> </td>
                    <td> </td>
                    @if($status == 0)
                    <td>Not Verified</td>
                    @elseif($status == 1)
                    <td>Approved</td>
                    @else
                    <td>Rejected</td>
                    @endif
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                @if(!is_null($approved_date))
                <tr>
                    <td> </td>
                    <td> <b>Approved Date
                        </b> </td>
                    <td> </td>
                    <td>{{ $approved_date }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                @endif

                @if(!is_null($approver_comment))
                <tr>
                    <td> </td>
                    <td> <b>Approver Comment
                        </b> </td>
                    <td> </td>
                    <td>{{ $approver_comment }} </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                    <td width="10"> </td>
                </tr>
                @endif
            </tbody>
        </table>
        <br>
        <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center">@if(is_null($approver_comment))
            Your requested has been processed as soon.
            @else
            Thanks for your request.
            @endif
        </p>
        <br>
    </div>
</div>
@include('layout.email-footer')