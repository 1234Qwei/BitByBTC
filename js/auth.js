let timerOn = true;

function timer(remaining) {
  var m = Math.floor(remaining / 60);
  var s = remaining % 60;
  
  m = m < 10 ? '0' + m : m;
  s = s < 10 ? '0' + s : s;
  document.getElementById('timer').innerHTML = 'Remaining Time is ' + m + ':' + s;
  remaining -= 1;
  
  if(remaining >= 0 && timerOn) {
	setTimeout(function() {
		timer(remaining);
	}, 1000);
	return;
  }

  if(!timerOn) {
	// Do validate stuff here
	return;
  }
  // Do timeout stuff here
  $('#resend').css('display','block');
}
function resendotp(url) {
    var user_id = $('#user_id').val();
        $.ajax({
            url: url,
            type: "GET",
			headers: {
				'X-CSRF-TOKEN': $('#csrf').html()
			},
            data : { 'user_id' : user_id},
			success: function(data) {
				if(data.responseType=='success'){
					$('#resend').css('display','none');
					timer(data.expirytime);
				}
			},
        });	
}
$(document).ready(function () {
    setTimeout(function() {
        $('#error').fadeOut('slow');
    }, 10000);

    $(".refbut").click(function () {
        $(".css-hiy16i").toggle();
    });
});
$(document).on("click", ".js-password-icon", function () {
    var input = $(this).parent().find(".password");
    if (input.attr("type") == "password") {
        input.attr("type", "text");
        $(this).attr("class", "fa fa-eye js-password-icon");
    } else {
        input.attr("type", "password");
        $(this).attr("class", "fa fa-eye-slash js-password-icon");
    }
});
$(function () {
    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Please enter the email",
            },
            password: {
                required: "Please provide a password",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".input_login").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // do other things for a valid form
            $("body")
                .find("#js-submit")
                .text("Loading...")
                .prop("disabled", true);

            form.submit();
        },
    });
    $("#registerForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
            mobile: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10,
            },
            password: {
                required: true,
                minlength: 8,
            },
            confirm_password: {
                equalTo: "#password",
                minlength: 8,
            },
            terms: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address",
            },
            mobile: {
                required: "Please enter a mobile number",
                number: "Please enter valid mobile number",
                minlength: "Please enter valid mobile number",
                maxlength: "Please enter valid mobile number",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long",
            },
            terms: "Please accept our terms",
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            if (element.attr("type") == "checkbox") {
                toastr.error("Please accept the terms & conditions.");
            } else {
                error.addClass("invalid-feedback");
                element.closest(".input_login").append(error);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // do other things for a valid form
            $("body")
                .find("#js-submit")
                .text("Loading...")
                .prop("disabled", true);

            form.submit();
        },
    });

    $("#forgetPasswordForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".input_login").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // do other things for a valid form
            $("body")
                .find("#js-submit")
                .text("Loading...")
                .prop("disabled", true);

            form.submit();
        },
    });

    $("#resetPasswordForm").validate({
        rules: {
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
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".input_login").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // do other things for a valid form
            $("body")
                .find("#js-submit")
                .text("Loading...")
                .prop("disabled", true);

            form.submit();
        },
    });

    $("#activationForm").validate({
        rules: {
            token: {
                required: true,
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".input_login").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // do other things for a valid form
            $("body")
                .find("#js-submit")
                .text("Loading...")
                .prop("disabled", true);
            form.submit();
        },
    });
});
