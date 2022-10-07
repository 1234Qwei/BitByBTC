@include('layout.home-header')
<div class="header-out-1">
  <div class="container">
    @include('account.header')
    <div class="package-2 gb-change-pass">
      <div class="package-title"> Change Password</div>
      <div class="e-ou-space">
        <form action="{{ route('change-password') }}" method="post" id="passwordForm" novalidate="novalidate" autocomplete="off">
          @csrf
          <div class="row">
            
            <div class="p-30">
              <div class="col-md-4 input-field">
                <label class="contact_title">Existing Password</label>
                <input type="password" name="oldpassword" id="oldpassword" class="enq-input password" id="oldpassword" placeholder="Existing Password" autocomplete="off" />
                <a role="button" class="js-password" data-id="oldpassword"><i class="fa fa-eye-slash"></i></a>
              </div>
              <div class="col-md-4 input-field">
                <label class="contact_title">New Password</label>
                <input type="password" name="password" id="password" class="enq-input password" id="password" placeholder="New Password" autocomplete="off" />
                <a role="button" class="js-password" data-id="password"><i class="fa fa-eye-slash"></i></a>
              </div>
              <div class="col-md-4 input-field">
                <label class="contact_title">Re-type Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="enq-input password" id="confirm_password" placeholder="Re-type Password" autocomplete="off" />
                <a role="button" class="js-password" data-id="confirm_password"><i class="fa fa-eye-slash"></i></a>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-3"></div>
              <div class="col-md-3"></div>
              <div class="col-md-6">
                <div class="sub-ouo pull-right">
                  <button type="submit" id="js-password-submit" class="form-btnn semibold"> Change Password </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $(document).on("click", ".js-password", function() {
    let id = $(this).attr("data-id");
    let input = $('body').find('#' + id);
    if (input.attr("type") == "password") {
      input.attr("type", "text");
      $(this).children().attr("class", "fa fa-eye");
    } else {
      input.attr("type", "password");
      $(this).children().attr("class", "fa fa-eye-slash");
    }
    return false;
  });
  $("#passwordForm").validate({
    rules: {
      oldpassword: {
        required: true,
      },
      password: {
        required: true,
        minlength: 8,
      },
      confirm_password: {
        equalTo: "#password",
        minlength: 8,
      },
    },
    messages: {
      oldpassword: {
        required: "Please enter the current password",
      },
      password: {
        required: "Please provide a password",
      },
    },
    errorElement: "span",
    errorPlacement: function(error, element) {
      error.addClass("invalid-feedback");
      element.closest(".input-field").append(error);
    },
    highlight: function(element, errorClass, validClass) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).removeClass("is-invalid");
    },
    submitHandler: function() {
      $('body').find('#js-password-submit').attr('disabled', 'disabled').text('Loading...');
      return true;
    },
  });
</script>
@endpush
@include('layout.home-footer')
