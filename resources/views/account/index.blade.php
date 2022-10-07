@include('layout.home-header')
<!--header-end--->
<div class="clear"></div>
<!--body-start--->
<div class="header-out-1">
    <div class="container">
        @include('account.header')
        <!-------------------2--->
        <div class="package-2">
            <div class="package-title"> Personal Information </div>
            <div class="e-ou-space">
                @include('account.profile-form', compact('data'))
            </div>
            <div class="clearfix"></div>
        </div>
        <!-------------------2--->
        <!-------------------3--->
        <div class="package-2">
            <div class="package-title"> Two Factor Authentication </div>
            <!-----1--->
            <div class="col-md-10">
                <div class="package-title-subbb"> <span><img src="{{ asset('img/auth-google.png') }}" /></span>
                    <div class="acc-ou-6">
                        <p>Google Authentication</p>
                        <div class="acc-ou-6-6"> To protect your account, please set Google Authenticator.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="acc-ou-7">
                    @if($data['user']->google2fa_secret)
                    <a href="{{ url('2fa-enable') }}" class="acc-ou-7-7">Disable</a>
                    @else
                    <a href="{{ url('2fa-enable') }}" class="acc-ou-7-7">Enable</a>
                    @endif
                </div>
            </div>
            <!-----1--->
            <!-----1--->
            <div class="col-md-10">
                <div class="package-title-subbb"> <span><img src="{{ asset('img/auth-sms.png') }}" /></span>
                    <div class="acc-ou-6">
                        <p>Sms Authentication</p>
                        <div class="acc-ou-6-6"> To protect your account, please set sms Authenticator.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="acc-ou-7">
                    <div class="acc-ou-7-7">Disable</div>
                </div>
            </div>
            <!-----1--->
            <!-----1--->
            <div class="col-md-10">
                <div class="package-title-subbb"> <span><img src="{{ asset('img/auth-email.png') }}" /></span>
                    <div class="acc-ou-6">
                        <p>E-mail Authentication</p>
                        <div class="acc-ou-6-6"> To protect your account, please set E-mail Authenticator.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="acc-ou-7">
                    <div class="acc-ou-7-7">Disable</div>
                </div>
            </div>
            <!-----1--->
        </div>
        <!-------------------3--->
        <!-------------------4--->
        <div class="package-3">
            <div class="package-title-1"> Security Setting </div>
            <div class="col-md-10">
                <div class="package-title-subbb"> <span><img src="{{ asset('img/security-icon.png') }}" /></span>
                    <div class="acc-ou-9">
                        <p>Security Setting</p>
                        <div class="acc-ou-9-9">Used for login.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="acc-ou-8">
                    <a href="{{ url('change-password')}}" class="acc-ou-8-8">Change</a>
                </div>
            </div>
            <div class="col-md-10">
                <div class="package-title-subbb"> <span><img src="{{ asset('img/email-icon.png') }}" /></span>
                    <div class="acc-ou-9">
                        <p> Email Setting</p>
                        <div class="acc-ou-9-9">Used for TFA, changing password, editing safety settings</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="acc-ou-8">
                    <a href="{{ url('notification-settings')}}" class="acc-ou-8-8">Reset</a>
                </div>
            </div>
        </div>
        <!-------------------4--->
        <!-------------------5--->
        <div class="package-2">
            <div class="package-title"> API KEY </div>
            <div class="col-md-10">
                <div class="package-title-subbb"> <span><img src="{{ asset('img/api_key.png') }}" /></span>
                    <div class="acc-ou-6">
                        <p>Enable API KEY</p>
                        <div class="acc-ou-6-6">Enable API access on your account to generate keys after kyc verification.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="acc-ou-7">
                    <div class="acc-ou-7-7">Enable</div>
                </div>
            </div>
        </div>
        <!-------------------5--->
        <div class="package-4">
            <table class="small_tb1" border="0" cellpadding="4px" cellspacing="4px">
                <tr>
                    <th colspan="4">
                        <div class="border-1">Recent Login</div>
                    </th>
                </tr>
                <tr>
                    <td>
                        <div class="border-2">Date</div>
                    </td>
                    <td>
                        <div class="border-2">Browser</div>
                    </td>
                    <td>
                        <div class="border-2">IP Address</div>
                    </td>
                    <td>
                        <div class="border-2">Location</div>
                    </td>
                </tr>
                @forelse($data['loggedInHistory'] as $history)
                <tr>
                    <td>
                        <div class="border-3">{{ $history->created_at }}</div>
                    </td>
                    <td>
                        <div class=" border-3">{{ $history->browser }}</div>
                    </td>
                    <td>
                        <div class="border-3">{{ $history->ip }}</div>
                    </td>
                    <td>
                        <div class="border-3">{{ $history->country ?? "None" }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No Recent History</td>
                </tr>

                @endforelse
            </table>
        </div>
        <!-------6-------->
    </div>
</div>
<!--body-end--->
@include('layout.home-footer')