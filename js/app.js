// require('./bootstrap');

(function ($) {
    "use strict";

    $.validator.setDefaults({
        submitHandler: function () {
            return true;
        },
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
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    $("#profileUpdateForm").validate({
        rules: {
            password: {
                minlength: 8,
            },
            confirm_password: {
                equalTo: "#password",
                minlength: 8,
            },
            name: {
                required: true,
                minlength: 4,
            },
            email: {
                email: true,
            },
            mobile: {
                minlength: 10,
                maxlength: 10,
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
                minlength: "Your name must be at least 4 characters long",
            },
            username: {
                required: "Please enter a username",
                minlength: "Your username must be at least 4 characters long",
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    $("#exchangeDepositForm").validate({
        rules: {
            deposit_coin: {
                required: true,
                min: function () {
                    return Number($("body").find("#deposit_min").val());
                },
                number: true,
            },
            bank_id: {
                required: function (element) {
                    return (
                        $("body").find("#deposit_currency").val() ==
                        "indian_rupee"
                    );
                },
            },
        },
        messages: {
            deposit_coin: {
                required: "Please enter a coin",
                number: "Your coin must be number format",
                min: "The coin has to be higher than {0}",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            if (element.attr("type") == "radio") {
                toastr.error("Please select the account type.");
            } else {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
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
            let currencyDeposit = $("body").find("#deposit_currency").val();
            if (currencyDeposit === "indian_rupee") {
            }
            form.submit();
            $("body")
                .find("#deposit_button")
                .text("Loading...")
                .prop("disabled", true);
        },
    });

    $("#exchangeSwapForm").validate({
        rules: {
            swap_from_coin: {
                required: true,
                number: true,
                min: function () {
                    return Number($("body").find("#deposit_min").val());
                },
                max: function () {
                    return Number(
                        $("body").find('input[name="available_coin"]').val()
                    );
                },
            },
            swap_currency: {
                required: true,
            },
        },
        messages: {
            swap_from_coin: {
                required: "Please select a currency",
                min: "The amount has to be higher than {0}",
                max: "The amount has to be lower than {0}",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // do other things for a valid form
            form.submit();
            $("body")
                .find("#swap_button")
                .text("Loading...")
                .prop("disabled", true);
        },
    });

    $("#swapUpdateForm").validate({
        rules: {
            status: {
                required: true,
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            // do other things for a valid form
            form.submit();
            $("body").find(".swap_update_button").prop("disabled", true);
            $("body").find("#swap_submit_button").text("Loading...");
        },
    });

    $("#depositUpdateForm").validate({
        rules: {
            status: {
                required: true,
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            //alert("HI");
            // do other things for a valid form
            $("body").find(".deposit_button").prop("disabled ", true);
            $("body").find("#deposit_submit").text("Loading...");
            // form.submit();
        },
    });

    $(document).on("change", "#deposit_currency", function () {
        let selected_currency = $(this).val();
        if (selected_currency !== "") {
            if (selected_currency === "indian_rupee") {
                $("body")
                    .find("#deposit_coin")
                    .attr("placeholder", "Deposit Amount");
                $("body").find(".address_section").addClass("hide");
                $("body").find("#fiat_currency_address").removeClass("hide");
            } else {
                $("body")
                    .find("#deposit_coin")
                    .attr("placeholder", "Deposit Coin");
                $("body").find(".address_section").removeClass("hide");
                $("body").find("#fiat_currency_address").addClass("hide");
            }
            var __url = $(this).attr("data-url");
            $.ajax({
                url: __url,
                type: "GET",
                dataType: "json",
                data: { coin: selected_currency },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                beforeSend: function () {
                    $("body").find("#depositCodeLoader").removeClass("hide");
                    $("body").find("#depositBarCode").addClass("hide");
                    $("body").find("#deposit_button").prop("disabled", true);
                    $("body").find("#deposit_coin").val("");
                    $("body").find("#deposit_min").val("");
                },
                success: function (response) {
                    if (response) {
                        $("body")
                            .find("#walletaddress")
                            .val(response["address"]);
                        $("body")
                            .find("#depositBarCode")
                            .attr("src", response["address_url"]);
                        $("body")
                            .find("#deposit_min")
                            .val(response["deposit_min"]);
                    } else {
                        $("body")
                            .find("#deposit_button")
                            .prop("disabled", true);
                        toastr.error("Something went wrong!");
                    }

                    setTimeout(function () {
                        $("body").find("#depositCodeLoader").addClass("hide");
                        $("body").find("#depositBarCode").removeClass("hide");
                        $("body")
                            .find("#deposit_button")
                            .prop("disabled", false);
                    }, 500);
                },
            });
        } else {
            $("body").find("#deposit_button").prop("disabled", true);
        }
        return false;
    });

    $(document).on("change", "#swap_from_currency", function () {
        let selected_currency = $(this).val();
        if (selected_currency !== "") {
            var __url = $(this).attr("data-url");
            $.ajax({
                url: __url,
                type: "POST",
                dataType: "json",
                data: { coin: selected_currency },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                beforeSend: function () {
                    $("body")
                        .find("#swap_button")
                        .text("Loading...")
                        .prop("disabled", true);
                    $("body").find("#swap_to_currency").val("");
                },
                success: function (response) {
                    if (response && response.status == true) {
                        if (!Number(response.balance)) {
                            resetSwap();
                        } else {
                            $("body")
                                .find('input[name="available_coin"]')
                                .val(response.balance);
                            $("body")
                                .find('input[name="swap_from_coin"]')
                                .val(response.balance);
                            $("body").find("#swap_min").val(response.min);
                            $("body")
                                .find("#swap_to_currency")
                                .prop("disabled", false);
                            $("body")
                                .find("#swap_to_currency")
                                .children("option")
                                .show();
                            $("body")
                                .find("#swap_to_currency")
                                .children(
                                    "option[value^=" + selected_currency + "]"
                                )
                                .hide();
                        }
                        $("body")
                            .find("#availableCoinBalance")
                            .html(response.balance + " " + response.symbol);
                        setTimeout(function () {
                            $("body")
                                .find("#swap_button")
                                .text("Buy / Sell / Swap !")
                                .prop(
                                    "disabled",
                                    response.balance ? false : true
                                );
                        }, 500);
                    } else {
                        toastr.error("Something went wrong!");
                        resetSwap();

                        setTimeout(function () {
                            $("body")
                                .find("#swap_button")
                                .text("Buy / Sell / Swap !")
                                .prop("disabled", true);
                        }, 500);
                    }
                },
            });
        } else {
            resetSwap();
            setTimeout(function () {
                $("body")
                    .find("#swap_button")
                    .text("Buy / Sell / Swap !")
                    .prop("disabled", true);
            }, 500);
        }
        return false;
    });

    $(document).on("change", "#swap_to_currency", function () {
        getPricingDetails();
        return false;
    });

    $(document).on("change", "#swap_from_coin", function () {
        getPricingDetails();
        return false;
    });

    $(document).on("change", "#swapCoin", function () {
        $("body").find("#js-swapcoin-loader").addClass("fa-spin");
        calculateValue();
        return false;
    });

    $(document).on("click", "#jsSwapAction", async function () {
        var id = $(this).attr("data-id");
        var __url = $(this).attr("data-url");
        $.ajax({
            url: __url,
            type: "GET",
            dataType: "json",
            data: {
                id: id,
                exchangeType: 2,
            },
            cache: false,
            success: function (response) {
                if (response.success) {
                    setTimeout(function () {
                        $("body").find("#swap-view").html(response.html);
                    }, 500);
                } else {
                    toastr.error("Something went wrong!");
                    return false;
                }
            },
        });

        $("#exchangeSwap").modal({
            backdrop: "static",
            keyboard: false,
        });
    });

    $(document).on("click", "#jsDepositAction", function () {
        $("#exchangeDeposit").modal({
            backdrop: "static",
            keyboard: false,
        });
        var id = $(this).attr("data-id");
        var __url = $(this).attr("data-url");
        $.ajax({
            url: __url,
            type: "GET",
            dataType: "json",
            data: {
                id: id,
                exchangeType: 1,
            },
            cache: false,
            success: function (response) {
                if (response.success) {
                    setTimeout(function () {
                        $("body").find("#deposit-view").html(response.html);
                    }, 500);
                } else {
                    toastr.error("Something went wrong!");
                    return false;
                }
            },
        });



        return false;
    });
	
    $(document).on("click", "#jsDepositActionIncoming", function () {
        $("#incomingCoinRequest").modal({
            backdrop: "static",
            keyboard: false,
        });
        var id = $(this).attr("data-id");
        var __url = $(this).attr("data-url");
        $.ajax({
            url: __url,
            type: "GET",
            dataType: "json",
            data: {
                id: id,
            },
            cache: false,
            success: function (response) {
                if (response.success) {
                    setTimeout(function () {
                        $("body").find("#request-view").html(response.html);
                    }, 500);
                } else {
                    toastr.error("Something went wrong!");
                    return false;
                }
            },
        });



        return false;
    });

    $("#jsDepositAction").on("hidden.bs.modal", function () {
        $("body").find("#exchange_type").text("");
        $("body").find("#swap_to").text("");
        $("body").find("#requested_date").text("");
        $("body").find("#approved_date").text("");
        $("body").find("#dd_ex_status").text("");
        $("body").find("#approver_comment").text("");
    });

    function getPricingDetails() {
        var getPriceURL = $("body").find("#get_price_url").val();
        var fromCoinSymbol = $("body")
            .find("#swap_from_currency option:selected", this)
            .attr("coinSymbol");
        var toCoinSymbol = $("body")
            .find("#swap_to_currency option:selected", this)
            .attr("coinSymbol");
        var swapFrom = $("body").find("#swap_from_currency").val();
        var swapTo = $("body").find("#swap_to_currency").val();
        var swapFromCoin = $("body").find("#swap_from_coin").val();

        if (
            swapFrom != "" &&
            swapTo != "" &&
            swapFromCoin != "" &&
            swapFromCoin > 0
        ) {
            $.ajax({
                url: getPriceURL,
                type: "GET",
                dataType: "json",
                data: {
                    swap_from: swapFrom,
                    swap_to: swapTo,
                    swap_from_coin: swapFromCoin,
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                beforeSend: function () {
                    $("body")
                        .find("#swap_button")
                        .text("Loading...")
                        .prop("disabled", true);
                    $("body")
                        .find(".swap-loader")
                        .removeClass("hide")
                        .addClass("fa-spin");
                },
                success: function (response) {
                    if (response.status) {
                        $("body")
                            .find("#swap_to_coin")
                            .val(response.convertPrice);
                        $("body")
                            .find("#js-exchange-price")
                            .html(
                                `1 ${fromCoinSymbol}  ≈  ${response.swapConvPrice}  ${toCoinSymbol}`
                            );
                        $("body")
                            .find("#js-fee")
                            .html(`${response.swapFees} ${fromCoinSymbol}`);
                        if (Number(response.convertPrice)) {
                            $("body")
                                .find("#swap_button")
                                .text("Buy / Sell / Swap !")
                                .prop("disabled", false);
                        } else {
                            $("body")
                                .find("#swap_button")
                                .text("Buy / Sell / Swap !")
                                .prop("disabled", true);
                        }
                    } else {
                        toastr.error("Something went wrong!");
                        $("body").find("#js-exchange-price").text("0.0000");
                        $("body").find("#js-fee").html("0.0000");
                        $("body").find("#swap_to_coin").val("");
                    }

                    $("body")
                        .find(".swap-loader")
                        .addClass("hide")
                        .removeClass("fa-spin");
                },
            });
        } else {
            $("body").find("#js-exchange-price").text("0.0000");
            $("body").find("#js-fee").html("0.0000");
            $("body").find("#swap_to_coin").val("");
        }

        setTimeout(function () {
            $("body").find(".swap-loader").removeClass("fa-spin");
        }, 500);

        return false;
    }

    function resetSwap() {
        $("body").find("#availableCoinBalance").html(0);
        $("body").find('input[name="available_coin"]').val(0);
        $("body").find('input[name="swap_from_coin"]').val(0);
        $("body").find("#swap_min").val(0);
        $("body").find("#swap_max").val(0);
        $("body").find("#swap_to_currency").val("").change();
        $("body").find("#swap_to_currency").prop("disabled", true);
        $("body").find("#js-exchange-price").text("0.0000");
        $("body").find("#js-fee").html("0.0000");
        $("body").find("#swap_to_coin").val("");
    }

    $("#withdrawForm").validate({
        rules: {
            walletaddress: {
                required: true,
            },
            amount: {
                required: true,
                number: true,
                min: function () {
                    return Number(
                        $("body").find('input[name="withdraw_min"]').val()
                    );
                },
                max: function () {
                    return Number(
                        $("body").find('input[name="available_amount"]').val()
                    );
                },
            },
            bank_id: {
                required: function (element) {
                    return $("body").find("#coin").val() == "indian_rupee";
                },
            },
        },
        messages: {
            walletaddress: {
                required: "Please enter your wallet address",
            },
            amount: {
                required: "Please enter your withdrawal amount",
                min: "The amount has to be higher than {0}",
                max: "The amount has to be lower than {0}",
                lessThan:
                    "The amount has to be lower or equalTo the available balance",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            if (element.attr("type") == "radio") {
                toastr.error("Please select the bank account.");
            } else {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    $.validator.addMethod("lessThan", function (value, element, param) {
        var $otherElement = $(param);
        return Number(value, 10) <= Number($otherElement.val(), 10);
    });

    $(document).on("click", "#js-submit", function () {
        if ($("#withdrawForm").valid()) {
            var tempId = $("body").find("#js-tempid").val();
            if (tempId == "") {
                sendOtp();
            } else {
                postWithdraw();
            }
        }
        return false;
    });

    function postWithdraw() {
        var tempId = $("body").find("#js-tempid").val();
        var csrf_token = $('meta[name="csrf-token"]').attr("content");
        var otp = $("body").find("#js-otp").val();
        $.ajax({
            url: $("body").find("#js-submit").attr("data-submit"),
            type: "POST",
            dataType: "json",
            data: { _token: csrf_token, dataid: tempId, otp: otp },
            beforeSend: function () {
                $("body").find(".js-progress").attr("disabled", "disabled");
                $("body").find("a.js-progress").addClass("disable-link");
                $("body").find("#js-loader").removeClass("hide");
            },
            success: function (response) {
                if (response.status === true) {
                    $("body").find(".js-progress").removeAttr("disabled");
                    $("body").find("#js-text").text("Submit");
                    $("body").find("#js-submit").removeClass("hide");
                    $("body").find("#js-loader").addClass("hide");
                    $("body").find("a.js-progress").removeClass("disable-link");
                    toastr.success(response.message);
                } else if (response.status === false) {
                    toastr.error(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
        });

        setTimeout(function () {
            window.location.href = "/withdraw-request";
        }, 1000);

        return false;
    }

    function sendOtp() {
        var walletaddress = $("body").find("#walletaddress").val();
        var amount = $("body").find("#amount").val();
        var remarks = $("body").find("#remarks").val();
        var coin_id = $("body").find("#coin_id").val();
        var coin = $("body").find("#coin").val();
        var bank_id = $('input[name="bank_id"]:checked').val();
        var is_withdraw_fee_checked =
            $('input[name="is_withdraw_fee_checked"]').is(":checked") == true
                ? 1
                : 0;
        $.ajax({
            url: $("body").find("#js-submit").attr("data-url"),
            type: "POST",
            dataType: "json",
            data: {
                is_withdraw_fee_checked: is_withdraw_fee_checked,
                coin: coin,
                coin_id: coin_id,
                walletaddress: walletaddress,
                amount: amount,
                remarks: remarks,
                bank_id: bank_id,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                $("body").find(".js-progress").attr("disabled", "disabled");
                $("body").find("a.js-progress").addClass("disable-link");
                $("body").find("#js-loader").removeClass("hide");
            },
            success: function (response) {
                if (response.status === true) {
                    $("body").find("#js-tempid").val(response.dataid);
                    $("body").find(".js-progress").removeAttr("disabled");
                    $("body").find("#js-otp-div").removeClass("hide");
                    $("body").find("#js-text").text("Submit");
                    $("body").find("#js-otp").attr("required", "required");
                    $("body").find("a.js-progress").removeClass("disable-link");
                    $("body").find("#js-loader").addClass("hide");
                } else if (response.status === false) {
                    toastr.error(response.message);
                    $("body").find(".js-progress").removeAttr("disabled");
                    $("body").find("#js-otp-div").addClass("hide");
                    $("body").find("#js-text").text("Submit");
                    $("body").find("a.js-progress").removeClass("disable-link");
                    $("body").find("#js-loader").addClass("hide");
                } else {
                    toastr.error(response.message);
                }
            },
        });
        return false;
    }

})(jQuery);
