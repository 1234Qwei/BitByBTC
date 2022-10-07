
<form id="profileForm" class="profile-box" method="post" action="{{ route('profile-update') }}">@csrf
    <div class="col-md-4 input-field">
        <div class="contact_title"> Full Name <span>*</span> </div>
        <input type="text" class="enq-input" name="fname" id="js-fname" placeholder="First Name" value="{{ $data['user']->first_name }}" />
    </div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> Last Name <span>*</span> </div>
        <input type="text" class="enq-input" name="lname" id="js-lname" placeholder="Last Name" value="{{ $data['user']->last_name }}" />
    </div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> Email Address <span>*</span> </div>
        <input type="email" class="enq-input" name="email" id="js-email" value="{{ $data['user']->email }}" placeholder="Email" disabled />
    </div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> Mobile-Number <span>*</span> </div>
        <input type="text" class="enq-input" pattern="[0-9]{10}" name="mobile" value="{{ $data['user']->mobile }}" placeholder="Mobile Number" disabled />
    </div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> Gender <span>*</span> </div>
        <select name="gender" id="gender" class="enq-input city-space-2">
            <option value="">Select Gender</option>
            <option value="male" @if($data['user']->gender == 'male') selected @endif>Male </option>
            <option value="female" @if($data['user']->gender == 'female') selected @endif>Female</option>
            <option value="others" @if($data['user']->gender == 'others') selected @endif>Others</option>
        </select>
    </div> 
    <div class="col-md-4 input-field">
        <div class="contact_title"> Date Of Birth <span>*</span> </div>
        <input class="enq-input enq-dob-input" name="dob" id="dob" type="date" value="{{ $data['user']->dob }}" />
    </div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> Address Line 1 <span>*</span> </div>
        <input type="text" class="enq-input" name="address" placeholder="Address" value="{{ $data['user']->address }}" />
    </div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> Address Line 2 </div>
        <input type="text" class="enq-input" name="address_1" placeholder="Address" value="{{ $data['user']->address_1 }}" />
    </div>
    <div class="col-md-4 input-field country-field">
        <div class="contact_title"> Country <span>*</span> </div> 
        <select name="country" id="country" data-state-url="{{ url('state') }}" class="enq-input select2">
            <option value="0" selected="selected" disabled> Select Country </option>
            @foreach($data['countries'] as $country)
            @if($data['user']->country == $country->id)
            <option value="{{ $country->id }}" selected="selected">{{ $country->name }}</option>
            @else
            <option value="{{ $country->id }}">{{ $country->name }}</option> 
            @endif
            @endforeach
        </select>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> State Or Province <span>*</span> </div>
        <input type="text" class="enq-input" name="state" placeholder="State" value="{{ $data['user']->state }}" />
    </div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> City <span>*</span> </div>
        <input type="text" class="enq-input" name="city" placeholder="City" value="{{ $data['user']->city }}" />
    </div>
    <div class="col-md-4 input-field">
        <div class="contact_title"> Zipcode Or Postcode <span>*</span> </div>
        <input class="enq-input" id="zipcode" name="zipcode" type="text" pattern="[0-9]{6}" placeholder="zipcode" value="{{ $data['user']->zipcode }}" />
    </div>
    <div class="clearfix"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="sub-ouo pull-right">
            <button type="submit" id="js-profile-submit" class="form-btnn semibold"> Update Profile </button>
        </div>
    </div>
</form>
@push('scripts')
<!-- Scripts -->
<script src="{{ asset('js/account.js') }}"></script>
@endpush
