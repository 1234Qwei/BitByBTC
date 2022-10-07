// require('./bootstrap');
let timerOn = true;

function timer(remaining) {
    var m = Math.floor(remaining / 60);
    var s = remaining % 60;

    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    document.getElementById('selltimer').innerHTML = 'Remaining Time is ' + m + ':' + s;
    remaining -= 1;

    if (remaining >= 0 && timerOn) {
        setTimeout(function () {
            timer(remaining);
        }, 1000);
        return;
    }

    if (!timerOn) {
        // Do validate stuff here
        return;
    }
    // Do timeout stuff here
    $('#resendotp').css('display', 'block');
}
function resendotp(url) {
    var user_id = $('#user_id').val();
    $.ajax({
        url: url,
        type: "GET",
        headers: {
            'X-CSRF-TOKEN': $('#csrf').html()
        },
        data: { 'user_id': user_id },
        success: function (data) {
            if (data.responseType == 'success') {
                $('#resendotp').css('display', 'none');
                timer(data.expirytime);
            }
        },
    });
}
(function ($) {
    "use strict";

    $('.sellorder').on('click', function () {
        var dataform = $(this).data('form');
        if (dataform != 'exchangeform') {
            var currentcoin = $('#coin_volume').val();
            var initial_price = $('#initial_price').val();
            var err = 0;
            if (currentcoin.length == 0 || $('#coin_volume').val() == 0) {
                $('#coinlimit_err').html('<h4>Minimum coin should be greater then 0.</h4>');
                err++;
            }

            if (initial_price.length == 0 || $('#initial_price').val() == 0) {
                $('#initial_price_err').html('<h4>Initial value should be greater then 0.</h4>');
                err++;
            }
            if (err > 0) {
                return false;
            }
        }
        let url = $(this).data('action');
        var action = "onClick=resendotp('" + url + "');"
        $.confirm({
            title: 'Sell order otp verification..',
            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group"><span id="error" style="color:red"></span><br><br>' +
                '<label>Enter your email otp</label><br><br>' +
                '<input type="text" placeholder="Enter your email otp" class="emailotp form-control" required /><br>' +
                '<span id="selltimer" style="margin-top: 10px;color: black;"></span><br><a ' + action + ' style="display:none;width: 75px;float:right" class="btn btn-danger btn-sm" id="resendotp">Resend</a>' +
                '</div>' +
                '</form>',
            buttons: {
                formSubmit: {
                    text: 'Submit',
                    btnClass: 'btn-blue',
                    action: function () {
                        var emailotp = this.$content.find('.emailotp').val();
                        if (!emailotp) {
                            $.alert('Please enter your OTP');
                            return false;
                        }
                        verifyotp(emailotp, url);

                        return false;
                    }
                },
                cancel: function () {
                    //close
                },
            },
            onContentReady: function () {
                // bind to events
                callotp(url);
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    });
})(jQuery);
function resendotp(actionurl) {
    $.ajax({
        url: actionurl,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { 'action': 'reSend' },
        success: function (data) {
            if (data.responseType == 'success') {
                $('#resendotp').css('display', 'none');
                timer(data.expirytime);
            } else {
                $('#error').html(data.messages);
            }
        },
    });
}
function verifyotp(emailotp, actionurl) {
    $.ajax({
        url: actionurl,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { 'emailotp': emailotp, 'action': 'VerifyOtp' },
        success: function (data) {
            if (data.responseType == 'success') {
                var sellformaction = $('#sellformaction').val();
                $('#sellUpdateForm').attr('action', sellformaction);
                $('#sellUpdateForm')[0].submit();
            } else {
                $('#error').html(data.messages);
            }
        },
    });
}
function callotp(actionurl) {
    $.ajax({
        url: actionurl,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { 'action': 'generateOtp' },
        success: function (data) {
            if (data.responseType == 'success') {
                $('#resendotp').css('display', 'none');
                timer(data.expirytime);
            }
        },
    });
}
$(document).on("change", "#coin_volume", function () {

    var existingcoin = $('#existingcoin').val();

    var totalcoin = $('#totalcoin').val();

    var currentcoin = $('#coin_volume').val();

    var pagetype = $('#pagetype').val();

    if (totalcoin == 0) {

        if (parseInt(existingcoin) > parseInt(currentcoin)) {

            balance = parseInt(existingcoin) - parseInt(currentcoin);
            if (pagetype == 'edit') {
                $('#totalcoin').val(balance);
            }

        }
    } else {
        if (parseInt(currentcoin) > parseInt(totalcoin)) {

            $('#coin_volume').val(0);

            $('#coinlimit_err').html('<h4> Please enter less then total coin....</h4>');

        } else {
            if (pagetype == 'edit') {

                var balance = parseInt(existingcoin) - parseInt(currentcoin);

                var incrementcoin = parseInt(totalcoin) + parseInt(balance);

                $('#totalcoin').val(incrementcoin);
            }
            $('#coinlimit_err').html('');
        }

    }
});

$(document).on("click", ".commonbuynow", function () {
    $(this).css('display', 'none');

});
$(document).on("click", ".cancel", function () {

    $("#reload-content").load(location.href + " #reload-content");

});

function getSearchValue() {

    var searchvalue = $('.search').val();

    var banks = $('.banks').val();

    sellOrderSearch(searchvalue, banks);
}

function sellOrderSearch(searchvalue, banks) {
    var actionurl = $('#url').val();
    $.ajax({
        url: actionurl,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { 'searchvalue': searchvalue, 'banks': banks, 'action': 'SellorderSearch' },
        success: function (data) {
            if (data.responseType == 'success') {
                var sellformaction = $('#sellformaction').val();
                $('#sellUpdateForm').attr('action', sellformaction);
                $('#sellUpdateForm')[0].submit();
            } else {
                $('#error').html(data.messages);
            }
        },
    });
}