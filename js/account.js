
(function ($) {
    "use strict";
    $("#profileForm").validate({
        rules: {
            fname: {
                required: true,
            },
            lname: {
                required: true,
            },
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
            gender: {
                required: true,
            },
            dob: {
                required: true,
            },
            address: {
                required: true,
            },
            country: {
                required: true,
            },
            city: {
                required: true,
            },
            zipcode: {
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
        },
    });

    $(document).on("change", "#country", function () {
        var __url = $(this).attr("data-state-url");
        var __country = $(this).val();
        $.ajax({
            url: __url,
            type: "GET",
            dataType: "json",
            data: { country: __country },
            beforeSend: function () {},
            success: function (response) {
                if (response) {
                    let state = $("body").find('input[name="state_id"]').val();
                    $(document)
                        .find("#state")
                        .select2({
                            placeholder: "Select a State",
                            data: response,
                        })
                        .select2("val", state);
                } else {
                    toastr.error("Something went wrong!");
                }
            },
        });
        return false;
    });

    $(document).on("change", "#state", function () {
        var __url = $(this).attr("data-city-url");
        var __state = $(this).val();
        $.ajax({
            url: __url,
            type: "GET",
            dataType: "json",
            data: { state: __state },
            beforeSend: function () {},
            success: function (response) {
                if (response) {
                    let city = $("body").find('input[name="city_id"]').val();
                    $(document)
                        .find("#city")
                        .select2({
                            placeholder: "Select a City",
                            data: response,
                        })
                        .select2("val", city);
                } else {
                    toastr.error("Something went wrong!");
                }
            },
        });
        return false;
    });
})(jQuery);
