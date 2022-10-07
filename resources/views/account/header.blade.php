<div class="col-md-6">
    @if (Request::is('account') || Request::is('profile-verification'))
        <div class="acc-ou-1">
            <a href="{{ url('account') }}">
                <div class="acc-ou-1-1">Account</div>
            </a>
        </div>
        <div class="acc-ou-2">
            <a href="{{ url('profile-verification') }}">
                <div class="acc-ou-2-2">Profile Verification</div>
            </a>
        </div>
    @else
        <div class="acc-ou-1">
            <a href="{{ url('account') }}">
                <div class="acc-ou-1-1"><i class="fa fa-arrow-circle-left"></i> Back</div>
            </a>
        </div>
    @endif
</div>
<div class="col-md-6"></div>
<div class="packages-bgg">
    <div class="package-1">
        @if ($data['loggedInHistory'] && count($data['loggedInHistory']) > 1)
            <div class="body-pac-head"> Last Logged in :
                {{ $data['loggedInHistory'][1]->created_at ?? $data['loggedInHistory'][0]->created_at }} IP:
                {{ $data['loggedInHistory'][1]->ip ?? $data['loggedInHistory'][0]->ip }}
            </div>
        @endif
        <div class="col-md-4">
            <div class="package-title-sub"> <span>
                </span>
                <div class="acc-ou-4">
                    <p id="js-fullname">{{ $data['user']->first_name . ' ' . $data['user']->last_name ?? 'Unknown' }}
                    </p>
                    <div class="acc-ou-4-4"> <img style="padding-top: 2px; float: left; padding-right: 10px;"
                            src="{{ asset('img/verify.png') }}" /> Unverified</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="package-title-subb"> <span><img src="{{ asset('img/mobile.png') }}" /></span>
                <div class="acc-ou-5">
                    <p>Mobile Number</p>
                    <div class="acc-ou-5-5"> {{ $data['user']->mobile }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="package-title-subb"> <span><img src="{{ asset('img/email.png') }}" /></span>
                <div class="acc-ou-5">
                    <p>Mail ID</p>
                    <div class="acc-ou-5-5">{{ $data['user']->email }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
