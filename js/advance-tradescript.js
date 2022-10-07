if (
    unable_place_order === null ||
    stop_price_above === null ||
    stop_price_below === null ||
    valid_amount === null ||
    stop_greater === null ||
    insufficient_bal === null ||
    valid_stop_price === null ||
    order_placed_success === null ||
    invalid_pair === null ||
    cancel_order === null ||
    order_cancel === null ||
    error_try === null ||
    valid_price === null ||
    enter_amount_more_than == null ||
    enter_price_more_than == null ||
    no_trade_history == null ||
    no_buy_orders == null ||
    no_sell_orders == null
) {
    var unable_place_order = "";
    var min_amount_msg = "";
    var min_price_msg = "";
    var stop_price_above = "";
    var stop_price_below = "";
    var valid_amount = "";
    var stop_greater = "";
    var insufficient_bal = "";
    var valid_stop_price = "";
    var order_placed_success = "";
    var invalid_pair = "";
    var cancel_order = "";
    var order_cancel = "";
    var error_try = "";
    var valid_price = "";
    var enter_amount_more_than = "";
    var enter_price_more_than = "";
    var no_trade_history = "";
    var no_buy_orders = "";
    var no_sell_orders = "";
}
responseData = {};

var decvalue;

$("#dec_value").change(function () {
    decvalue = $("#dec_value").val();
    getPairdetails(pairData.pair, decvalue);
});

$(".price").val("");
$(".amount").val("");
$(".btnPerc").click(function () {
    if (user_id != 0) {
        // alert($(this).html()+'==='+$(this).attr('ordertype')+'==='+$(this).attr('order'));
        var perc = $(this).html().slice(0, -1);
        var type = $(this).attr("type");
        var order = $(".type").val();
        calculation_perc(perc, order, type);
    } else {
        notif({
            msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Please Login to continue',
            type: "error",
        });
        return false;
    }
});
function calculation_perc(perc, order, type) {
    var price = $(".price").val();
    price = parseFloat(price);
    if (type == "buy") {
        if (order != "market") {
            if (!price) {
                price = pairData.lastPrice;
                $(".buyprice").val(price);
            }
        } else {
            price = pairData.lastPrice;
        }
        balance = pairData.from_bal;
        balance = parseFloat(balance);
        tot = (balance * parseFloat(perc)) / 100;
        amount = parseFloat(tot);
        amount = parseFloat(amount) / parseFloat(price);
        tott = parseFloat(price) * parseFloat(amount);

        if (balance <= 0) {
            notif({
                msg:
                    '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                    insufficient_bal +
                    " ",
                type: "error",
            });
            return false;
        }
        if (tot >= balance) {
            amount = parseFloat(amount) - parseFloat(0.00000001);
        }
        tot = parseFloat(price) * parseFloat(amount);
        if (amount < parseFloat(pairData.minAmount)) {
            notif({
                msg:
                    '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                    enter_amount_more_than +
                    " " +
                    pairData.minAmount +
                    " ",
                type: "error",
            });
            return false;
        }
        /*if(price < parseFloat(pairData.minPrice)){
          notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+enter_price_more_than+' '+pairData.minPrice+' ', type: "error" });
          return false;
         }*/
        $(".buytot").html(removeZero(parseFloat(tott).toFixed(8)));
        $(".buyamount").val(removeZero(parseFloat(amount).toFixed(8)));
    } else {
        if (order != "market") {
            if (!price) {
                price = pairData.lastPrice;
                $(".sellprice").val(price);
            }
        } else {
            price = pairData.lastPrice;
        }
        balance = pairData.to_bal;
        balance = parseFloat(balance);

        balance = parseFloat(balance);
        amount = (balance * parseFloat(perc)) / 100;
        tot = parseFloat(amount) * parseFloat(price);

        if (balance <= 0) {
            notif({
                msg:
                    '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                    insufficient_bal +
                    " ",
                type: "error",
            });
            return false;
        }
        if (amount < parseFloat(pairData.minAmount)) {
            notif({
                msg:
                    '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                    enter_amount_more_than +
                    " " +
                    pairData.minAmount +
                    " ",
                type: "error",
            });
            return false;
        }
        /*  if(price < parseFloat(pairData.minPrice)){
          notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+enter_price_more_than+' '+pairData.minPrice+' ', type: "error" });
          return false;
         }*/
        $(".sellamount").val(removeZero(parseFloat(amount).toFixed(8)));
        var tots = parseFloat(tot);
        $(".selltot").html(removeZero(parseFloat(tots).toFixed(8)));
    }
}
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (
        (charCode > 34 && charCode < 41) ||
        (charCode > 47 && charCode < 58) ||
        charCode == 46 ||
        charCode == 8 ||
        charCode == 9
    )
        return true;
    return false;
}

function calculation(a) {
    //console.log('a'+a);
    var cls = "." + a;
    var amount = $(cls + "amount").val();

    var price = $(cls + "price").val();
    //console.log('amount'+amount);
    var tot = parseFloat(amount) * parseFloat(price);
    var n = tot.toString();
    if (tot < 0) {
        var tot = 0;
    }
    if (
        amount != "" &&
        price != "" &&
        amount != 0 &&
        price != 0 &&
        !isNaN(amount) &&
        n.indexOf("e") == -1
    ) {
        $(cls + "tot").html(tot.toFixed(8));
    } else {
        $(cls + "tot").html("0.00000000");
    }
}

function order_placed(a, extype) {
    var cls = "." + a;
    var logincheck = check_user_login();
    var ordertype = $(".type").val();

    calculation(a);

    if (ordertype == "stop") {
        var price = $(cls + "stopprice").val();
        price = parseFloat(price);
        var price1 = parseFloat(pairData.lastPrice);
        if (a == "buy") {
            if (price1 >= price) {
                showerror(stop_price_above + " " + price1);
                return false;
            }
        } else {
            if (price1 <= price) {
                showerror(stop_price_below + " " + price1);
                return false;
            }
        }
    }
    if (logincheck) {
        //console.log("logincheck"+logincheck);
        var c = $(cls + "amount").val(),
            d = $(cls + "price").val();
        if (c == "" || c <= 0 || d == "" || d <= 0) {
            if (c == "" || c == "0") {
                showerror(valid_amount);
            } else {
                showerror(valid_price);
            }
            return false;
        }
        if (ordertype == "stoporder") {
            var triggerprice = $(cls + "stopprice").val();
            if (!triggerprice || triggerprice < 0) {
                showerror(stop_greater);
                return false;
            }
        }

        return order_confirm(a, extype);
    }
}
/*function order_confirm(a,extype)
{ 

  console.log("logincheck");
  var cls = '.'+a;
  var c  = $(cls+"amount").val();
  var d = $(cls+"price").val();
  var ordertype = $('.type').val();

  var multiply  = parseFloat(c)*parseFloat(d);
  if(multiply>0)
  {
    var tot = multiply;
  }
  else
  {
    var tot = 0;
  } 
  
  var mul = parseFloat(tot);
  if(a=="buy")
  {
    if(mul > pairData.from_bal)
    {  
      if(ordertype!='market'){
   

    { 
      showerror(insufficient_bal);
      return false;
    }
    }
  }
  else
  {     
    if(pairData.to_bal < parseFloat(c))
    {
      alert(pairData.to_bal);
      alert(c);

      showerror(insufficient_bal);
      return false;
    }
  }
  
}
return executeOrder(a,extype);
}*/
function order_confirm(a, extype) {
    var cls = "." + a;
    var c = $(cls + "amount").val();
    var d = $(cls + "price").val();
    var ordertype = $(".type").val();

    var multiply = parseFloat(c) * parseFloat(d);
    if (multiply > 0) {
        var tot = multiply;
    } else {
        var tot = 0;
    }

    var mul = parseFloat(tot);
    if (a == "buy") {
        if (mul > pairData.from_bal) {
            if (ordertype != "market") {
                showerror(insufficient_bal);
                return false;
            }
        }
    } else {
        if (pairData.to_bal < parseFloat(c)) {
            showerror(insufficient_bal);
            return false;
        }
    }

    return executeOrder(a, extype);
}
function executeOrder(a, extype) {
    calculation(a);
    var inc_val = (dec_val = 0);
    var cls = "." + a;
    var amount = $(cls + "amount").val();
    var ordertype = $(".type").val();
    var price = $(cls + "price").val();
    var total = $(cls + "tot").html();
    var triggerprice = 0;
    if (ordertype == "stop") {
        ordertype = "stoporder";
        var triggerprice = $(cls + "stopprice").val();
        if (!triggerprice) {
            showerror(valid_stop_price);
            return false;
        }
    }
    $(cls + "btn").prop("disabled", true);
    pair_id = pairData.pair_id;
    $.ajax({
        url: siteurl + "/createorder",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data:
            "amount=" +
            amount +
            "&price=" +
            price +
            "&total=" +
            total +
            "&order=" +
            ordertype +
            "&pair=" +
            pair_id +
            "&type=" +
            a +
            "&stopprice=" +
            triggerprice,
        beforeSend: function () {
            $(cls + "btn").html('<i class="fa fa-spinner fa-spin"></i>');
        },
        success: function (res) {
            $(cls + "btn").html(a);
            $(cls + "btn").prop("disabled", false);
            var res = res.replace(/(\r\n|\n|\r)/gm, "");
            var res1 = JSON.parse(res);
            if (res1.status == "success") {
                // if(ordertype == 'market'){
                socket.emit("receivemarketrequest", {
                    recMsg: "create",
                    recPair: pairData.pair,
                });
                // }else{

                //     if (res1.response['orders'] == 2) {
                //         //stop order created
                //         id = res1.response['details'];
                //         datetime = res1.response['datetime'];
                //         stop_orders = responseData.stop_orders;

                //         obj2 = {
                //             price: price,
                //             stopprice: triggerprice,
                //             total: total,
                //             type: a,
                //             ordertype: 'Stop Order',
                //             amount: amount,
                //             id: id,
                //             datetime: datetime,
                //         };
                //         if (stop_orders == 0) {
                //             var arr = new Array();
                //             arr.push(obj2);
                //             responseData.stop_orders = arr;
                //         } else {
                //             stop_orders.unshift(obj2);
                //             responseData.stop_orders = stop_orders;
                //         }
                //         getStopOrders();
                //     } else {

                //         var hist = '';
                //         newarray = res1.response['new_array'];
                //         datetime = res1.response['datetime'];
                //         update_type = '';
                //         type = res1.response['type'];
                //         if (res1.response['orders'] == 1) {
                //             //trade happen
                //             last_price = res1.response['last_price'];
                //             last_volume = res1.response['last_volume'];
                //             pairData.lastPrice = last_price;
                //             update_type = res1.response['existing_type'];
                //             hist = res1.response['tradehistory'];
                //             existing_array = res1.response['existing_array'];
                //             socket.emit('receiveorder', {
                //                 'recMsg': 'create',
                //                 'recPair': pairData.pair,
                //                 'new_array': newarray,
                //                 'existing_type': update_type,
                //                 'type': type,
                //                 'existing_array': existing_array
                //             });
                //             socket.emit('receiverequest', {
                //                 'recMsg': 'create',
                //                 'recPair': pairData.pair,
                //                 'recent_trade_history': hist
                //             });
                //             high = parseFloat(pairData.high);
                //             low = parseFloat(pairData.low);
                //             volume = parseFloat(pairData.volume);
                //             change = parseFloat(pairData.change);
                //             rate = parseFloat(pairData.rate);
                //             last_price = parseFloat(last_price);
                //             if(high < last_price){
                //                 pairData.high = last_price;
                //             }
                //             else if(low < last_price){
                //                 pairData.low = last_price;
                //             }
                //             volume = volume+parseFloat(last_volume);
                //             pairData.volume = volume;
                //             change_perc = last_price-rate;
                //             per = (change_perc / rate) * 100;
                //              newarray = {
                //                 volume: volume,
                //                 per: per,
                //                 high: pairData.high,
                //                 low: pairData.low,
                //                 last_price: last_price,
                //             };
                //              socket.emit('marketchanges', {
                //                 'recMsg': 'create',
                //                 'recPair': pairData.pair,
                //                 'market': newarray,
                //             });

                //         } else {
                //             socket.emit('receiveorder', {
                //                 'recMsg': 'create',
                //                 'recPair': pairData.pair,
                //                 'new_array': newarray,
                //                 'existing_type': '',
                //                 'type': type,
                //                 'existing_array': ''
                //             });
                //         }
                //         active = res1.response['active'];
                //         //trade happen
                //         if (res1.response['orders'] == 1) {
                //             active_order = res1.response['active_order'];
                //             socket.emit('receiveactiveorder', {
                //                 'recMsg': 'create',
                //                 'recPair': pairData.pair,
                //                 'active_order': active_order
                //             });
                //             obj_history = {
                //                 price: price,
                //                 total: total,
                //                 type: type,
                //                 status: 'Filled',
                //                 ordertype: ordertype,
                //                 datetime: datetime,
                //                 amount: amount,
                //             };

                //             if (active != 0) {
                //                 obj_history.amount = cal_amount_order = parseFloat(amount) - parseFloat(active.amount);
                //                 obj_history.total = removeZero(parseFloat(cal_amount_order) * parseFloat(active.price));
                //             }
                //             my_tradehistory_update = responseData.my_tradehistory;
                //             if (my_tradehistory_update == 0) {
                //                 var arr = new Array();
                //                 arr.push(obj_history);
                //                 responseData.my_tradehistory = arr;
                //             } else {
                //                 my_tradehistory_update.unshift(obj_history);
                //                 responseData.my_tradehistory = my_tradehistory_update;
                //             }
                //             getMyhistory();
                //             update_stop_orders = res1.response['stop_orders'];
                //         }

                //         if (active != 0) {
                //             open_orders = responseData.open_orders;
                //             obj2 = {
                //                 price: active.price,
                //                 total: active.total,
                //                 id: active.id,
                //                 type: active.type,
                //                 ordertype: active.ordertype,
                //                 datetime: active.datetime,
                //                 amount: active.amount,
                //             };
                //             if (open_orders == 0) {
                //                 var arr = new Array();
                //                 arr.push(obj2);
                //                 responseData.open_orders = arr;
                //             } else {
                //                 open_orders.unshift(obj2);
                //                 responseData.open_orders = open_orders;
                //             }
                //             getOpenOrders();
                //         }
                //         active_arr = res1.response['stop_orders'];
                //         //stoporder affected
                //         check_stop_records(active_arr);

                //     }

                // }
                showadvanceBalance();
                notif({
                    msg:
                        '<i class="fa fa-check-circle" aria-hidden="true"></i>' +
                        order_placed_success +
                        " ",
                    type: "success",
                });
                $(cls + "amount").val("");
                if (ordertype != "market") {
                    $(cls + "price").val("");
                }
                $(cls + "tot").html("0");
                $(cls + "stopprice").val("");
            } else {
                showerror(res1.message);
                $(cls + "amount").val("");
                if (ordertype != "market") {
                    $(cls + "price").val("");
                }
                $(cls + "tot").html("0");
                $(cls + "stopprice").val("");
                return false;
            }
        },
    });
    return false;
}

function getPairdetails(pair, decvalue = "") {
    if (decvalue == "") {
        var decvalue = 8;
    }
    $.ajax({
        url: siteurl + "/pair-data-advance/" + pair + "/" + decvalue,
        method: "GET",
        async: false,
        contentType: false,
        processData: false,
        success: function (data) {
            var designs = data;
            var status = designs.status;
            if (status == "success") {
                var result = designs.result;
                var trade = result.trade_data;
                tradeData = result.trade_data;
                pairData.amount_type = result.amount_type;
                pairData.price_type = result.price_type;
                pairData.price_length = result.price_length;
                pairData.amount_length = result.amount_length;
                pairData.trade_fee = parseFloat(removeZero(result.trade_fee));
                pairData.taker_trade_fee = parseFloat(
                    removeZero(result.taker_trade_fee)
                );
                pairData.minAmount = removeZero(
                    parseFloat(result.min_amt).toFixed(8)
                );
                pairData.minPrice = removeZero(
                    parseFloat(result.min_price).toFixed(8)
                );
                pairData.maxPrice = result.max_price;
                pairData.pair_id = result.pair_id;
                pairData.lastPrice = last_price = removeZero(
                    parseFloat(result.last_price).toFixed(8)
                );
                pairData.range = range = result.price_range;
                pairData.from_bal = result.from_bal;
                pairData.to_bal = result.to_bal;
                pairData.usd_val = result.usd_val;
                pairData.pair = pair;
                /* pairData.from_bal = parseFloat(result.from_bal).toFixed(8);
             pairData.to_bal = parseFloat(result.to_bal).toFixed(8);*/
                $(".cur_pair").html(
                    result.pair + '<i class="fa fa-fw fa-angle-down"></i>'
                );
                /*$('.from_bal').html(pairData.from_bal);
	        	 $('.to_bal').html(pairData.to_bal);*/
                $(".from_bal").html(result.from_bal);
                $(".to_bal").html(result.to_bal);
                $(".from_cur").html(result.from_cur);
                $(".to_cur").html(result.to_cur);
                $(".trade_fee").html(result.trade_fee);
                $(".taker_trade_fee").html(result.taker_trade_fee);
                $(".taker_trade_fee1").html(
                    "Taker " + result.taker_trade_fee + "%"
                );
                $(".trade_fee1").html("Maker " + result.trade_fee + "%");
                $(".lastprice").html(pairData.lastPrice);
                $(".buyprice").val(pairData.lastPrice);
                $(".sellprice").val(pairData.lastPrice);
                $(".price").val(pairData.lastPrice);
                $(".usd_val").html("  $" + result.usd_val);
                if (range == "posVal") {
                    $(".lastprice").html(
                        '<i class="fa fa-fw fa-arrow-up text-success"></i>' +
                            last_price
                    );
                    $(".lastprice").addClass("text-success");
                } else if (range == "negVal") {
                    $(".lastprice").html(
                        '<i class="fa fa-fw fa-arrow-down text-danger"></i>' +
                            last_price
                    );
                    $(".lastprice").addClass("text-danger");
                }
                if (tradeData.change > 0) {
                    $(".change").addClass("text-success");
                    $(".change").removeClass("text-success");
                } else {
                    $(".change").removeClass("text-danger");
                    $(".change").addClass("text-danger");
                }
                $(".change").html(tradeData.change + "%");
                $(".high").html(tradeData.high);
                $(".low").html(tradeData.low);
                $(".volume").html(tradeData.volume);
                pairData.high = tradeData.high;
                pairData.low = tradeData.low;
                pairData.volume = tradeData.volume;
                pairData.change = tradeData.change;
                $("#openOrdersTable").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $(".buyOrdersTable").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $(".sellOrdersTable").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $("#myTradeHistory").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $("#myTradeTable").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $("#tradeHistory").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $("#stopOrdersTable").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                /* $('.buytb').mCustomScrollbar({
        		  scrollButtons: {
        			enable: false
        		  },

        		  scrollbarPosition: 'inside',
        		  autoExpandScrollbar: true,
        		  theme: 'minimal-dark',
        		  axis: "y",
        		  setWidth: "auto"
        		});*/
                /*$('.selltb').mCustomScrollbar({
        		  scrollButtons: {
        			enable: false
        		  },

        		  scrollbarPosition: 'inside',
        		  autoExpandScrollbar: true,
        		  theme: 'minimal-dark',
        		  axis: "y",
        		  setWidth: "auto"
        		});*/
                var buy_orders = result.buy_orders;
                responseData.buy_orders = buy_orders;
                var historys = "";
                place_amount = 0;
                if (buy_orders != "" && buy_orders != 0) {
                    buy_orders_lenght = buy_orders.length;
                    for (count = 0; count < buy_orders_lenght; count++) {
                        //amount = buy_orders[count].amount;
                        amount = removeZero(
                            parseFloat(buy_orders[count].amount).toFixed(8)
                        );
                        amount1 = parseFloat(buy_orders[count].amount).toFixed(
                            8
                        );
                        amount_zero = amount1.match(/[0]+$/);
                        if (amount_zero == null) {
                            amountzero = "";
                        } else if (amount_zero == "00000000") {
                            amountzero = "0000000";
                        } else {
                            amountzero = amount_zero;
                        }

                        /*if(decvalue == 8) {
                          if(amountzero == '00000000') {
                            amountzero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(amountzero == '0000') {
                            amountzero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(amountzero == '00') {
                            amountzero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(amountzero == '0') {
                            amountzero = '';
                          }
                        }*/
                        if (amount > 0) {
                            place_amount = removeZero(
                                parseFloat(amount) + parseFloat(place_amount)
                            );
                            //total = buy_orders[count].total;
                            total = removeZero(
                                parseFloat(buy_orders[count].total).toFixed(8)
                            );
                            total1 = parseFloat(
                                buy_orders[count].total
                            ).toFixed(8);
                            total_zero = total1.match(/[0]+$/);

                            if (total_zero == null) {
                                totalzero = "";
                            } else if (total_zero == "00000000") {
                                totalzero = "0000000";
                            } else {
                                totalzero = total_zero;
                            }

                            /* if(decvalue == 8) {
                            if(totalzero == '00000000') {
                              totalzero = '0000000';
                            }
                          } else if(decvalue == 4) {
                            if(totalzero == '0000') {
                              totalzero = '000';
                            }
                          } else if(decvalue == 2) {
                             if(totalzero == '00') {
                              totalzero = '0';
                            }
                          } else if(decvalue == 1) {
                            if(totalzero == '0') {
                              totalzero = '';
                            }
                          }*/

                            //price = buy_orders[count].price;
                            price = removeZero(
                                parseFloat(buy_orders[count].price).toFixed(
                                    decvalue
                                )
                            );
                            price1 = parseFloat(
                                buy_orders[count].price
                            ).toFixed(decvalue);
                            price_zero = price1.match(/[0]+$/);
                            if (price_zero == null) {
                                pricezero = "";
                            } else {
                                pricezero = price_zero;
                            }

                            if (decvalue == 8) {
                                if (pricezero == "00000000") {
                                    pricezero = "0000000";
                                }
                            } else if (decvalue == 4) {
                                if (pricezero == "0000") {
                                    pricezero = "000";
                                }
                            } else if (decvalue == 2) {
                                if (pricezero == "00") {
                                    pricezero = "0";
                                }
                            } else if (decvalue == 1) {
                                if (pricezero == "0") {
                                    pricezero = "";
                                }
                            }
                            type = "buy";
                            number = buy_orders[count].number;
                            historys =
                                historys +
                                '<tr id="' +
                                number +
                                '" class="plc_order" onclick="placeOrder(1,' +
                                parseFloat(place_amount) +
                                "," +
                                parseFloat(price) +
                                ')" ><td class="posVal">' +
                                price +
                                '<span class="buytabtrailingzero green">' +
                                pricezero +
                                "</span></td><td>" +
                                amount +
                                '<span class="buytabtrailingzero white">' +
                                amountzero +
                                "</span></td><td>" +
                                total +
                                '<span class="buytabtrailingzero grey">' +
                                totalzero +
                                "</span></td></tr>";
                        }
                    }
                }
                if (historys != "") {
                    $(".buyOrdersTable .mCSB_container").html(historys);
                    //$('.buytb').html(historys);
                } else {
                    //$('.buytb').html('<div style="text-align:center;"><span class="posVal">'+no_buy_orders+'</span></div>');
                    $(".buyOrdersTable .mCSB_container").html(
                        '<div style="text-align:center;"><span class="posVal">' +
                            no_buy_orders +
                            "</span></div>"
                    );
                }

                var limit_buy_orders = result.limit_buy_orders;
                responseData.limit_buy_orders = limit_buy_orders;
                var historys = "";
                place_amount = 0;
                if (limit_buy_orders != "" && limit_buy_orders != 0) {
                    buy_orders_lenght = limit_buy_orders.length;
                    for (count = 0; count < buy_orders_lenght; count++) {
                        //amount = buy_orders[count].amount;
                        amount = removeZero(
                            parseFloat(limit_buy_orders[count].amount).toFixed(
                                8
                            )
                        );
                        amount1 = parseFloat(
                            limit_buy_orders[count].amount
                        ).toFixed(8);
                        amount_zero = amount1.match(/[0]+$/);
                        if (amount_zero == null) {
                            amountzero = "";
                        } else if (amount_zero == "00000000") {
                            amountzero = "0000000";
                        } else {
                            amountzero = amount_zero;
                        }

                        /*if(decvalue == 8) {
                            if(amountzero == '00000000') {
                              amountzero = '0000000';
                            }
                          } else if(decvalue == 4) {
                            if(amountzero == '0000') {
                              amountzero = '000';
                            }
                          } else if(decvalue == 2) {
                             if(amountzero == '00') {
                              amountzero = '0';
                            }
                          } else if(decvalue == 1) {
                            if(amountzero == '0') {
                              amountzero = '';
                            }
                          }*/
                        if (amount > 0) {
                            place_amount = removeZero(
                                parseFloat(amount) + parseFloat(place_amount)
                            );
                            //total = buy_orders[count].total;
                            total = removeZero(
                                parseFloat(
                                    limit_buy_orders[count].total
                                ).toFixed(8)
                            );
                            total1 = parseFloat(
                                limit_buy_orders[count].total
                            ).toFixed(8);
                            total_zero = total1.match(/[0]+$/);
                            if (total_zero == null) {
                                totalzero = "";
                            } else if (total_zero == "00000000") {
                                totalzero = "0000000";
                            } else {
                                totalzero = total_zero;
                            }

                            /*if(decvalue == 8) {
                              if(totalzero == '00000000') {
                                totalzero = '0000000';
                              }
                            } else if(decvalue == 4) {
                              if(totalzero == '0000') {
                                totalzero = '000';
                              }
                            } else if(decvalue == 2) {
                               if(totalzero == '00') {
                                totalzero = '0';
                              }
                            } else if(decvalue == 1) {
                              if(totalzero == '0') {
                                totalzero = '';
                              }
                            }*/

                            //price = buy_orders[count].price;
                            price = removeZero(
                                parseFloat(
                                    limit_buy_orders[count].price
                                ).toFixed(decvalue)
                            );
                            price1 = parseFloat(
                                limit_buy_orders[count].price
                            ).toFixed(decvalue);
                            price_zero = price1.match(/[0]+$/);
                            if (price_zero == null) {
                                pricezero = "";
                            } else {
                                pricezero = price_zero;
                            }

                            if (decvalue == 8) {
                                if (pricezero == "00000000") {
                                    pricezero = "0000000";
                                }
                            } else if (decvalue == 4) {
                                if (pricezero == "0000") {
                                    pricezero = "000";
                                }
                            } else if (decvalue == 2) {
                                if (pricezero == "00") {
                                    pricezero = "0";
                                }
                            } else if (decvalue == 1) {
                                if (pricezero == "0") {
                                    pricezero = "";
                                }
                            }
                            type = "buy";
                            number = limit_buy_orders[count].number;
                            historys =
                                historys +
                                '<tr id="' +
                                number +
                                '" class="plc_order" onclick="placeOrder(1,' +
                                amount +
                                "," +
                                parseFloat(price) +
                                ')"><td class="posVal">' +
                                price +
                                '<span class="buyselltabtrailingzero green">' +
                                pricezero +
                                "</span></td><td>" +
                                amount +
                                '<span class="buyselltabtrailingzero white">' +
                                amountzero +
                                "</span></td><td>" +
                                total +
                                '<span class="buyselltabtrailingzero grey">' +
                                totalzero +
                                "</span></td></tr>";
                        }
                    }
                }
                if (historys != "") {
                    //$('.buyOrdersTable .mCSB_container').html(historys);
                    $(".buytb").html(historys);
                } else {
                    $(".buytb").html(
                        '<div style="text-align:center;"><span class="posVal">' +
                            no_buy_orders +
                            "</span></div>"
                    );
                    //$('.buyOrdersTable .mCSB_container').html('<div style="text-align:center;"><span class="posVal">'+no_buy_orders+'</span></div>');
                }

                var sell_orders = result.sell_orders;
                place_amount = 0;
                responseData.sell_orders = sell_orders;
                var historys = "";
                if (sell_orders != "" && sell_orders != 0) {
                    sell_orders_length = sell_orders.length;
                    for (count = 0; count < sell_orders_length; count++) {
                        //amount = sell_orders[count].amount;
                        amount = removeZero(
                            parseFloat(sell_orders[count].amount).toFixed(8)
                        );
                        amount1 = parseFloat(sell_orders[count].amount).toFixed(
                            8
                        );
                        amount_zero = amount1.match(/[0]+$/);
                        if (amount_zero == null) {
                            amountzero = "";
                        } else if (amount_zero == "00000000") {
                            amountzero = "0000000";
                        } else {
                            amountzero = amount_zero;
                        }

                        /*if(decvalue == 8) {
                          if(amountzero == '00000000') {
                            amountzero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(amountzero == '0000') {
                            amountzero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(amountzero == '00') {
                            amountzero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(amountzero == '0') {
                            amountzero = '';
                          }
                        }*/
                        if (amount > 0) {
                            place_amount = removeZero(
                                parseFloat(amount) + parseFloat(place_amount)
                            );
                            //total = sell_orders[count].total;
                            total = removeZero(
                                parseFloat(sell_orders[count].total).toFixed(8)
                            );
                            total1 = parseFloat(
                                sell_orders[count].total
                            ).toFixed(8);
                            total_zero = total1.match(/[0]+$/);
                            if (total_zero == null) {
                                totalzero = "";
                            } else if (total_zero == "00000000") {
                                totalzero = "0000000";
                            } else {
                                totalzero = total_zero;
                            }

                            /*if(decvalue == 8) {
                          if(totalzero == '00000000') {
                            totalzero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(totalzero == '0000') {
                            totalzero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(totalzero == '00') {
                            totalzero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(totalzero == '0') {
                            totalzero = '';
                          }
                        }*/
                            //price = sell_orders[count].price;
                            price = removeZero(
                                parseFloat(sell_orders[count].price).toFixed(
                                    decvalue
                                )
                            );
                            price1 = parseFloat(
                                sell_orders[count].price
                            ).toFixed(decvalue);
                            price_zero = price1.match(/[0]+$/);
                            if (price_zero == null) {
                                pricezero = "";
                            } else {
                                pricezero = price_zero;
                            }

                            if (decvalue == 8) {
                                if (pricezero == "00000000") {
                                    pricezero = "0000000";
                                }
                            } else if (decvalue == 4) {
                                if (pricezero == "0000") {
                                    pricezero = "000";
                                }
                            } else if (decvalue == 2) {
                                if (pricezero == "00") {
                                    pricezero = "0";
                                }
                            } else if (decvalue == 1) {
                                if (pricezero == "0") {
                                    pricezero = "";
                                }
                            }

                            number = sell_orders[count].number;
                            historys =
                                historys +
                                '<tr id="' +
                                number +
                                '" class="plc_order" onclick="placeOrder(2,' +
                                parseFloat(place_amount) +
                                "," +
                                parseFloat(price) +
                                ')" ><td class="negVal">' +
                                price +
                                '<span class="selltabtrailingzero red">' +
                                pricezero +
                                "</span></td><td>" +
                                amount +
                                '<span class="selltabtrailingzero white">' +
                                amountzero +
                                "</span></td><td>" +
                                total +
                                '<span class="selltabtrailingzero grey">' +
                                totalzero +
                                "</span></td></tr>";
                        }
                    }
                }
                var sell_orderss = result.sell_orderss;
                place_amount = 0;
                responseData.sell_orderss = sell_orderss;
                var historyss = "";
                if (sell_orderss != "" && sell_orderss != 0) {
                    sell_orderss_length = sell_orderss.length;
                    for (count = 0; count < sell_orderss_length; count++) {
                        //amount = sell_orderss[count].amount;
                        amount = removeZero(
                            parseFloat(sell_orderss[count].amount).toFixed(8)
                        );
                        amount1 = parseFloat(
                            sell_orderss[count].amount
                        ).toFixed(8);
                        amount_zero = amount1.match(/[0]+$/);
                        if (amount_zero == null) {
                            amountzero = "";
                        } else if (amount_zero == "00000000") {
                            amountzero = "0000000";
                        } else {
                            amountzero = amount_zero;
                        }

                        /*if(decvalue == 8) {
                          if(amountzero == '00000000') {
                            amountzero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(amountzero == '0000') {
                            amountzero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(amountzero == '00') {
                            amountzero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(amountzero == '0') {
                            amountzero = '';
                          }
                        }*/

                        if (amount > 0) {
                            place_amount = removeZero(
                                parseFloat(amount) + parseFloat(place_amount)
                            );
                            //total = sell_orderss[count].total;
                            total = removeZero(
                                parseFloat(sell_orderss[count].total).toFixed(8)
                            );
                            total1 = parseFloat(
                                sell_orderss[count].total
                            ).toFixed(8);
                            total_zero = total1.match(/[0]+$/);
                            if (total_zero == null) {
                                totalzero = "";
                            } else if (total_zero == "00000000") {
                                totalzero = "0000000";
                            } else {
                                totalzero = total_zero;
                            }

                            /*if(decvalue == 8) {
                          if(totalzero == '00000000') {
                            totalzero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(totalzero == '0000') {
                            totalzero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(totalzero == '00') {
                            totalzero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(totalzero == '0') {
                            totalzero = '';
                          }
                        }*/
                            //price = sell_orderss[count].price;
                            price = removeZero(
                                parseFloat(sell_orderss[count].price).toFixed(
                                    decvalue
                                )
                            );
                            price1 = parseFloat(
                                sell_orderss[count].price
                            ).toFixed(decvalue);
                            price_zero = price1.match(/[0]+$/);
                            if (price_zero == null) {
                                pricezero = "";
                            } else {
                                pricezero = price_zero;
                            }

                            if (decvalue == 8) {
                                if (pricezero == "00000000") {
                                    pricezero = "0000000";
                                }
                            } else if (decvalue == 4) {
                                if (pricezero == "0000") {
                                    pricezero = "000";
                                }
                            } else if (decvalue == 2) {
                                if (pricezero == "00") {
                                    pricezero = "0";
                                }
                            } else if (decvalue == 1) {
                                if (pricezero == "0") {
                                    pricezero = "";
                                }
                            }
                            number = sell_orderss[count].number;
                            historyss =
                                historyss +
                                '<tr id="' +
                                number +
                                '" class="plc_order" onclick="placeOrder(2,' +
                                parseFloat(place_amount) +
                                "," +
                                parseFloat(price) +
                                ')" ><td class="negVal">' +
                                price +
                                '<span class="selltabtrailingzero red">' +
                                pricezero +
                                "</span></td><td>" +
                                amount +
                                '<span class="selltabtrailingzero white">' +
                                amountzero +
                                "</span></td><td>" +
                                total +
                                '<span class="selltabtrailingzero grey">' +
                                totalzero +
                                "</span></td></tr>";
                        }
                    }
                }
                if (historyss != "") {
                    $(".sellOrdersTable .mCSB_container").html(historyss);
                } else {
                    $(".sellOrdersTable .mCSB_container").html(
                        '<div style="text-align:center;"><span class="negVal">' +
                            no_sell_orders +
                            "</span></div>"
                    );
                }

                var limit_sell_orderss = result.limit_sell_orderss;
                place_amount = 0;
                responseData.limit_sell_orderss = limit_sell_orderss;
                var historyss = "";
                if (limit_sell_orderss != "" && limit_sell_orderss != 0) {
                    sell_orderss_length = limit_sell_orderss.length;
                    for (count = 0; count < sell_orderss_length; count++) {
                        //amount = sell_orderss[count].amount;
                        amount = removeZero(
                            parseFloat(
                                limit_sell_orderss[count].amount
                            ).toFixed(8)
                        );
                        amount1 = parseFloat(
                            limit_sell_orderss[count].amount
                        ).toFixed(8);
                        amount_zero = amount1.match(/[0]+$/);
                        if (amount_zero == null) {
                            amountzero = "";
                        } else if (amount_zero == "00000000") {
                            amountzero = "0000000";
                        } else {
                            amountzero = amount_zero;
                        }

                        /*if(decvalue == 8) {
                          if(amountzero == '00000000') {
                            amountzero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(amountzero == '0000') {
                            amountzero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(amountzero == '00') {
                            amountzero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(amountzero == '0') {
                            amountzero = '';
                          }
                        }*/
                        if (amount > 0) {
                            place_amount = removeZero(
                                parseFloat(amount) + parseFloat(place_amount)
                            );
                            //total = sell_orderss[count].total;
                            total = removeZero(
                                parseFloat(
                                    limit_sell_orderss[count].total
                                ).toFixed(8)
                            );
                            total1 = parseFloat(
                                limit_sell_orderss[count].total
                            ).toFixed(8);
                            total_zero = total1.match(/[0]+$/);
                            if (total_zero == null) {
                                totalzero = "";
                            } else if (total_zero == "00000000") {
                                totalzero = "0000000";
                            } else {
                                totalzero = total_zero;
                            }

                            /*if(decvalue == 8) {
                          if(totalzero == '00000000') {
                            totalzero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(totalzero == '0000') {
                            totalzero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(totalzero == '00') {
                            totalzero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(totalzero == '0') {
                            totalzero = '';
                          }
                        }*/

                            //price = sell_orderss[count].price;
                            price = removeZero(
                                parseFloat(
                                    limit_sell_orderss[count].price
                                ).toFixed(decvalue)
                            );
                            price1 = parseFloat(
                                limit_sell_orderss[count].price
                            ).toFixed(decvalue);
                            price_zero = price1.match(/[0]+$/);
                            if (price_zero == null) {
                                pricezero = "";
                            } else {
                                pricezero = price_zero;
                            }

                            if (decvalue == 8) {
                                if (pricezero == "00000000") {
                                    pricezero = "0000000";
                                }
                            } else if (decvalue == 4) {
                                if (pricezero == "0000") {
                                    pricezero = "000";
                                }
                            } else if (decvalue == 2) {
                                if (pricezero == "00") {
                                    pricezero = "0";
                                }
                            } else if (decvalue == 1) {
                                if (pricezero == "0") {
                                    pricezero = "";
                                }
                            }
                            number = limit_sell_orderss[count].number;
                            historyss =
                                historyss +
                                '<tr id="' +
                                number +
                                '" class="plc_order" onclick="placeOrder(2,' +
                                amount +
                                "," +
                                parseFloat(price) +
                                ')"><td class="negVal">' +
                                price +
                                '<span class = "sellbuytabtrailingzero red">' +
                                pricezero +
                                "</span></td><td>" +
                                amount +
                                '<span class = "sellbuytabtrailingzero white">' +
                                amountzero +
                                "</td><td>" +
                                total +
                                '<span class = "sellbuytabtrailingzero grey">' +
                                totalzero +
                                "</td></tr>";
                        }
                    }
                }

                if (historyss != "") {
                    $(".selltb").html(historyss);
                } else {
                    // $('.sellOrdersTable .mCSB_container').html('<div style="text-align:center;"><span class="negVal">'+no_sell_orders+'</span></div>');
                    $(".selltb").html(
                        '<div style="text-align:center;"><span class="negVal">' +
                            no_sell_orders +
                            "</span></div>"
                    );
                }
                var open_orders = result.open_orders;
                responseData.open_orders = open_orders;
                getOpenOrders();

                var stop_orders = result.stop_orders;
                responseData.stop_orders = stop_orders;
                getStopOrders();

                // $('#myTradeHistory .mCSB_container').html(result.my_orders);
                var my_orders = result.my_orders;
                responseData.my_tradehistory = my_orders;
                getMyhistory();

                var trade_history = result.market_orders;
                var historys = "";
                if (trade_history != "" && trade_history != 0) {
                    trade_history_lenght = trade_history.length;
                    for (count = 0; count < trade_history_lenght; count++) {
                        orderTime = trade_history[count].datetime;
                        amount = trade_history[count].amount;
                        isBuyerMaker = trade_history[count].isBuyerMaker;

                        amount = removeZero(
                            parseFloat(trade_history[count].amount).toFixed(8)
                        );
                        amount1 = parseFloat(
                            trade_history[count].amount
                        ).toFixed(8);
                        amount_zero = amount1.match(/[0]+$/);
                        if (amount_zero == null) {
                            amountzero = "";
                        } else if (amount_zero == "00000000") {
                            amountzero = "0000000";
                        } else {
                            amountzero = amount_zero;
                        }

                        /*if(decvalue == 8) {
                          if(amountzero == '00000000') {
                            amountzero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(amountzero == '0000') {
                            amountzero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(amountzero == '00') {
                            amountzero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(amountzero == '0') {
                            amountzero = '';
                          }
                        }*/
                        //price = trade_history[count].price;
                        price = removeZero(
                            parseFloat(trade_history[count].price).toFixed(8)
                        );
                        price1 = parseFloat(trade_history[count].price).toFixed(
                            8
                        );
                        price_zero = price1.match(/[0]+$/);
                        if (price_zero == null) {
                            pricezero = "";
                        } else if (price_zero == "00000000") {
                            pricezero = "0000000";
                        } else {
                            pricezero = price_zero;
                        }

                        /*if(decvalue == 8) {
                          if(pricezero == '00000000') {
                            pricezero = '0000000';
                          }
                        } else if(decvalue == 4) {
                          if(pricezero == '0000') {
                            pricezero = '000';
                          }
                        } else if(decvalue == 2) {
                           if(pricezero == '00') {
                            pricezero = '0';
                          }
                        } else if(decvalue == 1) {
                          if(pricezero == '0') {
                            pricezero = '';
                          }
                        }*/
                        sellorderId = trade_history[count].sellorderId;
                        buyorderId = trade_history[count].buyorderId;
                        old_key = parseInt(count) + 1;

                        if (sellorderId > buyorderId) {
                            class_name = "text-danger";
                            cls_name = "red";
                        } else {
                            if (isBuyerMaker == true) {
                                class_name = "text-success";
                                cls_name = "green";
                            } else {
                                class_name = "text-danger";
                                cls_name = "red";
                            }
                        }

                        if (
                            old_key < trade_history_lenght ||
                            old_key < trade_history_lenght
                        ) {
                            old_price = trade_history[old_key].price;

                            /*
                            if (old_price > price)
                                class_name = 'text-danger';*/
                        }
                        historys =
                            historys +
                            "<tr><td>" +
                            orderTime +
                            '</td><td class="' +
                            class_name +
                            '">' +
                            amount +
                            '<span class="tradehistorytrailingzero ' +
                            cls_name +
                            '">' +
                            amountzero +
                            "</span></td><td>" +
                            price +
                            '<span class="tradehistorytrailingzero grey">' +
                            pricezero +
                            "</span></td></tr>";
                    }
                }
                responseData.tradehistory = historys;
                if (historys != "") {
                    $("#tradeHistory .mCSB_container").html(historys);
                } else {
                    $("#tradeHistory .mCSB_container").html(no_trade_history);
                }

                // $('#openOrdersTable .mCSB_container').html(result.open_orders);
                // $('#myTradeHistory .mCSB_container').html(result.my_orders);
                // $('#myTradeTable .mCSB_container').html(result.my_filled_orders);
            } else {
                notif({
                    msg:
                        '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                        invalid_pair +
                        " ",
                    type: "error",
                });
            }
        },
        error: function (error) {
            notif({
                msg:
                    '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                    invalid_pair +
                    " ",
                type: "error",
            });
        },
    });
}

function check_user_login() {
    if (user_id == "" || user_id == undefined || user_id == 0) {
        /* $('#atrLogin').modal('show');
    return false;*/
        window.location.href = siteurl + "/login";

        return false;
    }
    return true;
}
function showerror(msg) {
    notif({
        msg:
            '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + msg,
        type: "error",
    });
}

function toFixed(x) {
    x = parseFloat(x);
    if (Math.abs(x) < 1.0) {
        var e = parseInt(x.toString().split("e-")[1]);
        if (e) {
            x *= Math.pow(10, e - 1);
            x = "0." + new Array(e).join("0") + x.toString().substring(2);
        }
    } else {
        var e = parseInt(x.toString().split("+")[1]);
        if (e > 20) {
            e -= 20;
            x /= Math.pow(10, e);
            x += new Array(e + 1).join("0");
        }
    }
    return x;
}
function removeZero(string) {
    string = string.toString();
    return string.replace(/^0+(\d)|(\d)0+$/gm, "$1$2");
}
function cancelOrder(val) {
    if (confirm(cancel_order)) {
        $.ajax({
            url: siteurl + "/cancelOrder",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: { tradeid: val },
            success: function (res) {
                var res = res.replace(/(\r\n|\n|\r)/gm, "");
                var res1 = JSON.parse(res);
                ordertype = res1.ordertype;
                if (res1.status == "success") {
                    showadvanceBalance();
                    socket.emit("receivemarketrequest", {
                        recMsg: "create",
                        recPair: pairData.pair,
                    });
                    // cancel_id = res1.cancel_id;
                    // datetime1 = res1.datetime;
                    // if (ordertype == 'stoporder') {
                    //     var removeItem = cancel_id;
                    //     stop_orders = responseData.stop_orders;
                    //     var filterObj = stop_orders.filter(function(e) {
                    //         return e.id == cancel_id;
                    //     });

                    //     total = parseFloat(filterObj[0].price) * parseFloat(filterObj[0].amount);
                    //     obj2 = {
                    //         price: filterObj[0].price,
                    //         stopprice: filterObj[0].stopprice,
                    //         status: 'Cancelled',
                    //         type: filterObj[0].type,
                    //         ordertype: filterObj[0].ordertype,
                    //         datetime: datetime1,
                    //         total: total,
                    //         amount: filterObj[0].amount,
                    //     };

                    //     my_tradehistory = responseData.my_tradehistory;
                    //     if (my_tradehistory == 0) {
                    //         var arr = new Array();
                    //         arr.push(obj2);
                    //         responseData.my_tradehistory = arr;
                    //     } else {
                    //         my_tradehistory.unshift(obj2);
                    //         responseData.my_tradehistory = my_tradehistory;
                    //     }
                    //     getMyhistory();

                    //     y = jQuery.grep(stop_orders, function(value) {
                    //         return value.id != removeItem;
                    //     });
                    //     responseData.stop_orders = y;
                    //     getStopOrders();

                    // }

                    //     var hist = '';
                    //     newarray = res1.response['new_array'];
                    //     type = res1.response['type'];
                    //     existing_type = res1.response['existing_type'];
                    //     hist = res1.response['tradehistory'];
                    //     existing_array = res1.response['existing_array'];

                    //     socket.emit('receiveorder', {
                    //         'recMsg': 'cancel',
                    //         'recPair': pairData.pair,
                    //         'new_array': newarray,
                    //         'existing_type': existing_type,
                    //         'type': type,
                    //         'existing_array': existing_array
                    //     });
                    //     var removeItem = cancel_id;
                    //     open_orders = responseData.open_orders;
                    //     var filterObj = open_orders.filter(function(e) {
                    //         return e.id == cancel_id;
                    //     });

                    //     total = parseFloat(filterObj[0].price) * parseFloat(filterObj[0].amount);
                    //     obj2 = {
                    //         price: filterObj[0].price,
                    //         status: 'Cancelled',
                    //         type: filterObj[0].type,
                    //         ordertype: filterObj[0].ordertype,
                    //         datetime: datetime1,
                    //         total: total,
                    //         amount: filterObj[0].amount,
                    //     };

                    //     my_tradehistory = responseData.my_tradehistory;
                    //     if (my_tradehistory == 0) {
                    //         var arr = new Array();
                    //         arr.push(obj2);
                    //         responseData.my_tradehistory = arr;
                    //     } else {
                    //         my_tradehistory.unshift(obj2);
                    //         responseData.my_tradehistory = my_tradehistory;
                    //     }
                    //     getMyhistory();

                    //     y = jQuery.grep(open_orders, function(value) {
                    //         return value.id != removeItem;
                    //     });
                    //     responseData.open_orders = y;
                    //     getOpenOrders();

                    notif({
                        msg:
                            '<i class="fa fa-check-circle" aria-hidden="true"></i>' +
                            order_cancel +
                            " ",
                        type: "success",
                    });
                } else {
                    notif({
                        msg:
                            '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                            " " +
                            res,
                        type: "error",
                    });
                }
            },
            error: function () {
                notif({
                    msg:
                        '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
                        error_try +
                        " ",
                    type: "error",
                });
            },
        });
    }
}
function showadvanceBalance() {
    $.ajax({
        url: siteurl + "/show-advance-data",
        method: "GET",
        success: function (res) {
            if (res) {
                $("#balance").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                pair = pairData.pair;
                pairSplit = pair.split("_");
                pairData.firstCurr = pairSplit[0];
                pairData.secondCurr = pairSplit[1];
                // pairData.to_bal = $('#'+pairData.firstCurr+'_bal').html();
                // pairData.from_bal = $('#'+pairData.secondCurr+'_bal').html();
                $("#balance .mCSB_container").html(res);
                $(".to_bal").html($("#" + pairData.firstCurr + "_bal").html());
                $(".from_bal").html(
                    $("#" + pairData.secondCurr + "_bal").html()
                );
            }
        },
    });
}
function show_adv_order(ordertype, trade) {
    $(".tot").html(0);
    $(".amount").val("");
    $(".price").removeAttr("disabled");
    //$('.price').val('');
    $(".stop").addClass("hide");
    $(".type").val(ordertype);
    $(".nomarket").removeClass("hide");
    $(".price").css("display", "block");
    if (ordertype == "market") {
        $(".price").css("display", "none");
        price = pairData.lastPrice;
        $(".price").val(price);
        $(".nomarket").addClass("hide");
        $(".price").attr("disabled", true);
    } else if (ordertype == "stop") {
        $(".stop").val("");
        $(".stop").removeClass("hide");
    }
}

function tradePairChange(pair) {
    pairData.pair = pair;
    pairSplit = pair.split("_");
    pairData.firstCurr = pairSplit[0];
    pairData.secondCurr = pairSplit[1];
    $("#selected_pair").text(pairSplit[0] + "/" + pairSplit[1]);
    getPairdetails(pair, "");
    window.history.pushState("", "", pair);
    advanceChart(pair);
    $(".coinDropDownMenu").hide();
    $(".all_active_pairs").closest("tr").removeClass("active_pair");
    $("#active_pair_" + pair)
        .closest("tr")
        .addClass("active_pair");
    $(".coinDrop .dropdown-menu").removeClass("show");
    if ($(window).width() >= 1200) {
        chart_wrap();
        $(window).bind("resize", chart_wrap);

        orders_wrap();
        $(window).bind("resize", orders_wrap);

        advtrade_wrap();
        $(window).bind("resize", advtrade_wrap);

        buysell_wrap();
        $(window).bind("resize", buysell_wrap);

        buysell_wrap1();
        $(window).bind("resize", buysell_wrap1);

        limitnew_wrap();
        $(window).bind("resize", limitnew_wrap);
    }
}

function advanceChart(pair) {
    var backClr = "#0a141e";
    var gridClr = "#111";
    var textClr = "#fff";
    var cssFile = "dark_style.css";
    var widget = new TradingView.widget({
        fullscreen: true,
        tvwidgetsymbol: pair,
        symbol: pair,
        style: "1",
        precision: 3,
        show_popup_button: true,
        popup_width: "1050",
        popup_height: "250",
        toolbar_bg: backClr,
        container_id: "chart_container",
        datafeed: new Datafeeds.UDFCompatibleDatafeed(
            siteurl + "/chart" + "/" + pair
        ),
        library_path: library_path,
        withdateranges: true,
        allow_symbol_change: false,
        interval: "1",
        locale: "en",
        theme: "light",
        height: "372px",
        save_image: false,
        hideideas: true,
        custom_css_url: cssFile,
        debug: false,
        drawings_access: {
            type: "black",
            tools: [{ name: "Regression Trend" }],
        },
        /*        "disabled_features": ["use_localstorage_for_settings","dome_widget","display_market_status","display_header_toolbar_chart","header_compare","header_undo_redo","compare_symbol","header_settings","study_dialog_search_control","caption_buttons_text_if_possible","header_screenshot","volume_force_overlay","header_widget","left_toolbar"],
         */
        disabled_features: [
            "use_localstorage_for_settings",
            "dome_widget",
            "display_market_status",
            "header_compare",
            "header_undo_redo",
            "compare_symbol",
            "study_dialog_search_control",
            "caption_buttons_text_if_possible",
            "volume_force_overlay",
            "left_toolbar",
        ],

        overrides: {
            //"mainSeriesProperties.style": 8,
            "paneProperties.background": backClr,
            "paneProperties.horzGridProperties.color": gridClr,
            "paneProperties.vertGridProperties.color": gridClr,
            "symbolWatermarkProperties.transparency": 90,
            "scalesProperties.textColor": textClr,
        },
    });
}
function placeOrder(type, amount, price) {
    amount = removeZero(parseFloat(amount).toFixed(8));
    price = removeZero(parseFloat(price).toFixed(8));
    $(".tot").html(0);
    $(".price").removeAttr("readonly");
    $(".advbuyform").removeClass("active show");
    $(".advbuylimit").addClass("active show");
    $(".price").css("display", "block");
    if (type == "2") {
        $(".buyamount").val(amount);
        $(".buyprice").val(price);
        calculation("buy");
    } else {
        $(".sellamount").val(amount);
        $(".sellprice").val(price);
        calculation("sell");
    }
    $(".stop").addClass("hide");
    $(".type").val("limit");
}

function showMarket() {
    $.ajax({
        url: siteurl + "/coin-pairs",
        method: "GET",
        dataType: "json",
        success: function (res) {
            if (res) {
                var designs = res;

                $(".m-btc").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $(".m-eth").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $(".m-bnb").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $(".m-own").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $(".m-fav").mCustomScrollbar({
                    scrollButtons: {
                        enable: false,
                    },

                    scrollbarPosition: "inside",
                    autoExpandScrollbar: true,
                    theme: "minimal-dark",
                    axis: "y",
                    setWidth: "auto",
                });
                $(".m-own .mCSB_container").html(designs.OWN);
                $(".m-btc .mCSB_container").html(designs.BTC);
                $(".m-eth .mCSB_container").html(designs.ETH);
                $(".m-bnb .mCSB_container").html(designs.BNB);
                $(".m-fav .mCSB_container").html(designs.Fav);
                var cls = ".active_pair_" + pairData.pair;
                $(cls).parents("tr").addClass("active_pair");
                $(".tab-pane-trade").removeClass("active");
                $(cls).parents('div[class^="tab-pane"]').addClass("active");
                $(cls).parents('div[class^="tab-pane"]').removeClass("fade");
                var target = $(cls)
                    .parents('div[class^="tab-pane"]')
                    .attr("id");
                /*var target1 = target.substring(0, target.length - 1);*/
                $('a[data-target="#' + target + '"]').addClass("active");
                /*$('a[data-target="#'+target1+'"]').addClass('active');*/
            }
        },
    });
}
function favPair(element, pair_id) {
    if (user_id != 0) {
        if ($(element).html() == '<i class="fa fa-fw fa-star-o"></i>') {
            $(".no-fav").hide();
            $(element).html('<i class="fa fa-fw fa-star"></i>');
            $(".tab-" + pair_id).html('<i class="fa fa-fw fa-star"></i>');
            $(element).addClass("portlet-star-cnt");
            var cont = $(element).parent("tr").html();
            $(".m-fav .mCSB_container").append(
                '<tr class="fav' + pair_id + '">' + cont + "</tr>"
            );
        } else {
            $(element).html('<i class="fa fa-fw fa-star-o"></i>');
            $(element).addClass("portlet-star-cnt");
            $(".fav" + pair_id).remove();
            $(".tab-" + pair_id).html('<i class="fa fa-fw fa-star-o"></i>');
        }

        $.ajax({
            url: siteurl + "/addFav",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: "pair_id=" + pair_id,
            beforeSend: function () {},
            success: function (res1) {
                if (res1 == "success") {
                } else {
                }
            },
        });
    }
}

$(document).ready(function () {
    $(".adv_search,.adv_mob_search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".m-fav tr,.m-btc tr,.m-usdt tr,.m-eth tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $(".exc-topbar-dd").click(function () {
        if ($(".exc-topbar-dd").hasClass("clicked")) {
            $(".exc-topbar-dd").removeClass("clicked");
            $(".dropdown-menu1").removeClass("show");
        } else {
            $(".exc-topbar-dd").addClass("clicked");
            $(".dropdown-menu1").addClass("show");
        }
    });
    // $(document).click(function(e){
    //   if (!$(e.target).hasClass("dropdown-toggle clicked") && $(e.target).parents(".coinDrop").length === 0)
    //     {
    //      $('.dropdown-toggle').removeClass('clicked');
    //      $('.dropdown-menu1').removeClass('show');
    //     }
    //   });
});

//socket
var currentUrl = document.location.origin;
// alert(currentUrl);
// var currentUrl = "http://localhost";
//console.log(currentUrl);
//var currentUrl = 'http://157.230.20.165';
var socketUrl = "http://3.132.91.16";
var socket = io.connect(socketUrl, { secure: true });

socket.on("pairData", function (res) {
    var msg = res.msge;
    if (msg == "cancel" || msg == "create") {
        if (res.paire == pairData.pair) {
            getPairdetails(pairData.pair, "");
        }
    }
});

socket.on("orderBook", function (res) {
    var msg = res.msge;
    if (res.paire == pairData.pair) {
        var existing_type = "";
        type = res.type;
        newarray = res.new_array;
        existing_type = res.existing_type;
        existing_array = res.existing_array;
        if (msg == "create") {
            if (type == "sell") {
                add = 1;
                add_orders = responseData.sell_orders;
                sub_orders = responseData.buy_orders;
            } else {
                add = 0;
                sub_orders = responseData.sell_orders;
                add_orders = responseData.buy_orders;
            }
        } else {
            if (existing_type == "sell") {
                add = 0;
                sub_orders = responseData.sell_orders;
            } else {
                add = 1;
                sub_orders = responseData.buy_orders;
            }
        }
        if (existing_type || msg == "cancel") {
            if (sub_orders != 0) {
                $.each(existing_array, function (i, value) {
                    $.map(sub_orders, function (element, index) {
                        if (
                            parseFloat(element.price) == parseFloat(value.price)
                        ) {
                            var cal_amount =
                                parseFloat(element.amount) -
                                parseFloat(value.amount);
                            sub_orders[index].amount = cal_amount;
                            sub_orders[index].cls = "advbookupdate";
                        }
                    });
                });

                if (add == 0) {
                    update_book("sell");
                    responseData.sell_orders = sub_orders;
                } else {
                    responseData.buy_orders = sub_orders;
                    update_book("buy");
                }
            } else {
            }
        }
        if (newarray != 0) {
            if (add_orders != 0) {
                updated = 0;
                $.each(add_orders, function (i, value) {
                    if (
                        parseFloat(value.price) == parseFloat(newarray[0].price)
                    ) {
                        updated = 1;
                        add_orders[i].amount =
                            parseFloat(value.amount) +
                            parseFloat(newarray[0].amount);
                        add_orders[i].cls = "advbookupdate";
                    }
                });
                if (updated == 0) {
                    obj2 = {
                        price: newarray[0].price,
                        amount: newarray[0].amount,
                        cls: "advbookupdate",
                    };

                    add_orders.push(obj2);
                }

                if (add == 1) {
                    update_book("sell");
                    responseData.sell_orders = add_orders;
                } else {
                    responseData.buy_orders = add_orders;
                    update_book("buy");
                }
            } else {
                var arr = new Array();
                $.each(newarray, function (i, obj) {
                    arr.push(obj);
                });
                if (add == 1) {
                    responseData.sell_orders = arr;
                    update_book("sell");
                } else {
                    responseData.buy_orders = arr;
                    update_book("buy");
                }
            }
        }
    }
});

function update_book(type) {
    if (type == "sell") {
        sell_orders = responseData.sell_orders;
        sell_orders = $.grep(sell_orders, function (a, b) {
            return a.amount > 0;
        });
        sell_orders.sort(function (a, b) {
            return parseFloat(a.price) - parseFloat(a.price);
        });

        var historys = "";
        var historys1 = "";
        place_amount = 0;
        if (sell_orders != "" && sell_orders != 0) {
            sell_orders_length = sell_orders.length;
            for (count = 0; count < sell_orders_length; count++) {
                //amount = removeZero(parseFloat(sell_orders[count].amount));
                amount = parseFloat(sell_orders[count].amount).toFixed(8);
                var cls = sell_orders[count].cls ? sell_orders[count].cls : "";
                responseData.sell_orders[count].cls = "";
                //price = removeZero(parseFloat(sell_orders[count].price));
                price = parseFloat(sell_orders[count].price).toFixed(8);
                if (
                    price > 0 &&
                    amount > 0 &&
                    amount != "" &&
                    amount != undefined &&
                    amount != 0 &&
                    !isNaN(amount)
                ) {
                    //total = removeZero((parseFloat(amount) * parseFloat(price)));
                    total = parseFloat(sell_orders[count].total).toFixed(8);
                    pairId = "sell_" + count;
                    place_amount =
                        parseFloat(amount) + parseFloat(place_amount);
                    historys =
                        historys +
                        '<tr id="' +
                        pairId +
                        '" class="sell' +
                        count +
                        "  plc_order " +
                        cls +
                        '" onclick="placeOrder(2,' +
                        parseFloat(place_amount) +
                        "," +
                        parseFloat(price) +
                        ')" ><td class="negVal">' +
                        price +
                        "</td><td>" +
                        amount +
                        "</td><td>" +
                        total +
                        "</td></tr>";
                }
            }

            /*for (count = 0; count < 8 ; count++) {
                //amount = removeZero(parseFloat(sell_orders[count].amount));
                amount = parseFloat(sell_orders[count].amount).toFixed(8);
                var cls = sell_orders[count].cls?sell_orders[count].cls:'';
                responseData.sell_orders[count].cls = '';
                //price = removeZero(parseFloat(sell_orders[count].price));
                price = parseFloat(sell_orders[count].price).toFixed(8);
                if (price > 0 && amount > 0 && amount != "" && amount != undefined && amount != 0 && !isNaN(amount)) {
                    //total = removeZero((parseFloat(amount) * parseFloat(price)));
                    total = parseFloat(sell_orders[count].total).toFixed(8);
                    pairId = 'sell_'+count;
                    place_amount = parseFloat(amount)+parseFloat(place_amount);
                    historys1 = historys1 + '<tr id="'+pairId+'" class="sell'+count+'  plc_order '+cls+'" onclick="placeOrder(2,' + parseFloat(place_amount) + ',' + parseFloat(price) + ')" ><td class="negVal">' + price + '</td><td>' + amount + '</td><td>' + total + '</td></tr>';
                     
                }
            }*/
        }

        if (historys != "") {
            $(".sellOrdersTable .mCSB_container").html(historys);
        } else {
            $(".sellOrdersTable .mCSB_container").html(
                '<div style="text-align:center;"><span class="negVal">' +
                    no_sell_orders +
                    "</span></div>"
            );
        }
        if (historys != "") {
            $(".selltb").html(historys);
        } else {
            $(".selltb").html(
                '<div style="text-align:center;"><span class="negVal">' +
                    no_sell_orders +
                    "</span></div>"
            );
        }
    } else {
        var buy_orders = responseData.buy_orders;
        buy_orders = $.grep(buy_orders, function (a, b) {
            return a.amount > 0;
        });
        buy_orders.sort(function (a, b) {
            return parseFloat(b.price) - parseFloat(a.price);
        });
        var historys = "";
        var historys1 = "";
        place_amount = 0;
        if (buy_orders != "" && buy_orders != 0) {
            buy_orders_lenght = buy_orders.length;
            for (count = 0; count < buy_orders_lenght; count++) {
                //amount = removeZero(parseFloat(buy_orders[count].amount));
                amount = parseFloat(buy_orders[count].amount).toFixed(8);
                //price = removeZero(parseFloat(buy_orders[count].price));
                price = parseFloat(buy_orders[count].price).toFixed(8);
                //console.log(buy_orders[count].cls);
                var cls = buy_orders[count].cls ? buy_orders[count].cls : "";
                responseData.buy_orders[count].cls = "";
                if (
                    price > 0 &&
                    amount > 0 &&
                    amount != "" &&
                    amount != undefined &&
                    amount != 0 &&
                    !isNaN(amount)
                ) {
                    //total = removeZero((parseFloat(amount) * parseFloat(price)));
                    total = parseFloat(buy_orders[count].total).toFixed(8);
                    type = "buy";
                    place_amount =
                        parseFloat(amount) + parseFloat(place_amount);
                    pairId = "buy_" + count;
                    historys =
                        historys +
                        '<tr id="' +
                        pairId +
                        '" class="buy' +
                        count +
                        "   plc_order " +
                        cls +
                        '" onclick="placeOrder(1,' +
                        parseFloat(place_amount) +
                        "," +
                        parseFloat(price) +
                        ')" ><td class="posVal">' +
                        price +
                        "</td><td>" +
                        amount +
                        "</td><td>" +
                        total +
                        "</td></tr>";
                }
            }

            /*for (count = 0; count < 6; count++) {

                //amount = removeZero(parseFloat(buy_orders[count].amount));
                amount = parseFloat(buy_orders[count].amount).toFixed(8);
                //price = removeZero(parseFloat(buy_orders[count].price));
                price = parseFloat(buy_orders[count].price).toFixed(8);
                console.log(buy_orders[count].cls);
                var cls = buy_orders[count].cls?buy_orders[count].cls:'';
                responseData.buy_orders[count].cls = '';
                if (price > 0 && amount > 0 && amount != "" && amount != undefined && amount != 0 && !isNaN(amount)) {
                    //total = removeZero((parseFloat(amount) * parseFloat(price)));
                    total = parseFloat(buy_orders[count].total).toFixed(8);
                    type = 'buy';
                    place_amount = parseFloat(amount)+parseFloat(place_amount);
                    pairId = 'buy_'+count;
                    historys1 = historys1 + '<tr id="'+pairId+'" class="buy'+count+'   plc_order '+cls+'" onclick="placeOrder(1,' + parseFloat(place_amount) + ',' + parseFloat(price) + ')" ><td class="posVal">' + price + '</td><td>' + amount + '</td><td>' + total + '</td></tr>';
                     
                }
            }*/
        }
        if (historys != "") {
            $(".buyOrdersTable .mCSB_container").html(historys);
        } else {
            $(".buyOrdersTable .mCSB_container").html(
                '<div style="text-align:center;"><span class="posVal">' +
                    no_buy_orders +
                    "</span></div>"
            );
        }

        if (historys != "") {
            $(".buytb").html(historys);
        } else {
            $(".buytb").html(
                '<div style="text-align:center;"><span class="posVal">' +
                    no_buy_orders +
                    "</span></div>"
            );
        }
    }
    $(".advbookupdate")
        .addClass("advbookupdate")
        .delay(600)
        .queue(function (next) {
            $(this).removeClass("advbookupdate");
            next();
        });
}

socket.on("pairHistory", function (res) {
    var msg = res.msge;
    if (msg == "create") {
        if (res.paire == pairData.pair) {
            var hist = res.history;
            if (responseData.tradehistory == "") {
                var recent_price = 0;
                $("#tradeHistory .mCSB_container").html("");
            } else {
                var recent_price = $("#tradeHistory")
                    .closest("table")
                    .find("tbody tr:eq(0) td:eq(1)")
                    .html();
            }
            $.each(hist, function (i, value) {
                class_name = "posVal";
                price = value["price"];

                if (i > 0) {
                    j = i - 1;
                    recent_price = hist[j]["price"];
                }
                if (recent_price > price) {
                    class_name = "negVal";
                }
                amount = removeZero(value["amount"].toString());
                orderTime = value["datetime"];
                historys =
                    '<tr class="advbookupdate"><td>' +
                    orderTime +
                    '</td><td class="' +
                    class_name +
                    '">' +
                    price +
                    "</td><td>" +
                    amount +
                    "</td></tr>";
                $("#tradeHistory .mCSB_container").prepend(historys);
                responseData.tradehistory = historys;
            });
        }
        $(".advbookupdate")
            .addClass("advbookupdate")
            .delay(600)
            .queue(function (next) {
                $(this).removeClass("advbookupdate");
                next();
            });
    }
});

socket.on("active_update", function (res) {
    var msg = res.msge;
    if (res.paire == pairData.pair) {
        update_active_order = res.update_activeorder;
        $.each(update_active_order, function (index, value) {
            $.each(value, function (i, value_update) {
                if (i == update_user_id) {
                    //console.log(value_update);
                    var active = value_update.update_type;
                    obj_history = {
                        price: value_update.price,
                        total: value_update.total,
                        type: value_update.type,
                        status: "Filled",
                        ordertype: value_update.ordertype,
                        datetime: value_update.datetime,
                        amount: value_update.amount,
                    };

                    my_tradehistory_update = responseData.my_tradehistory;
                    if (my_tradehistory_update == 0) {
                        var arr = new Array();
                        arr.push(obj_history);
                        responseData.my_tradehistory = arr;
                    } else {
                        my_tradehistory_update.unshift(obj_history);
                        responseData.my_tradehistory = my_tradehistory_update;
                    }
                    getMyhistory();
                    update_open_orders = responseData.open_orders;
                    objs1 = jQuery.grep(update_open_orders, function (value) {
                        return value.id == value_update.id;
                    });
                    //console.log(objs1);
                    objIndex = update_open_orders.findIndex(
                        (obj) => obj.id == value_update.id
                    );
                    if (objIndex > 0 || objIndex == 0) {
                        // alert(update_open_orders[objIndex].amount+'='+value_update.amount);
                        cal_amount_order =
                            parseFloat(update_open_orders[objIndex].amount) -
                            parseFloat(value_update.amount);
                        total =
                            parseFloat(cal_amount_order) *
                            parseFloat(value_update.price);
                        if (cal_amount_order == 0) {
                            objs = jQuery.grep(
                                update_open_orders,
                                function (value) {
                                    return value.id != value_update.id;
                                }
                            );
                            responseData.open_orders = objs;
                        } else {
                            update_open_orders[objIndex].amount =
                                cal_amount_order;
                            update_open_orders[objIndex].total = total;
                            responseData.open_orders = update_open_orders;
                        }
                    }
                    getOpenOrders();
                }
            });
        });
    }
});

socket.on("stoporder_update", function (res) {
    var msg = res.msge;

    if (res.paire == pairData.pair) {
        update_stoporder = res.update_stoporder;
        $.each(update_stoporder, function (i, value) {
            if (i == update_user_id) {
                $.map(value, function (element, index) {
                    stop_orders = responseData.stop_orders;
                    objIndex = stop_orders.findIndex(
                        (obj) => obj.id == element
                    );
                    createStop = stop_orders[objIndex];

                    objs = jQuery.grep(stop_orders, function (value) {
                        return value.id != element;
                    });
                    responseData.stop_orders = objs;

                    obj2 = {
                        datetime: createStop.datetime,
                        ordertype: createStop.ordertype,
                        type: createStop.type,
                        amount: createStop.amount,
                        price: createStop.price,
                        total: createStop.total,
                        id: createStop.id,
                    };

                    open_orders = responseData.open_orders;
                    if (open_orders == 0) {
                        var arr = new Array();
                        arr.push(obj2);
                        responseData.open_orders = arr;
                    } else {
                        open_orders.unshift(obj2);
                        responseData.open_orders = open_orders;
                    }
                });
                getOpenOrders();
                getStopOrders();
            }
        });
    }
});
socket.on("update_marketchanges", function (res) {
    if (res.paire == pairData.pair) {
        market = res.market;
        pairData.lastPrice = market.last_price;
        pairData.change = market.per;
        pairData.high = market.high;
        pairData.low = market.low;
        pairData.volume = market.volume;
        $(".lastprice").html(market.last_price);
        // if (market.per > 0) {
        //     $('.change').addClass('text-success');
        //     $('.change').removeClass('text-success');
        // } else {
        //     $('.change').removeClass('text-danger');
        //     $('.change').addClass('text-danger');
        // }
        // $('.change').html(market.per + '%');
        // $('.volume').html(market.volume);
        $(".high").html(market.high);
        $(".low").html(market.low);
    }
});
socket.on("order_update", function (res) {
    var msg = res.msge;

    if (res.paire == pairData.pair) {
        update_order = res.update_order;
        $.each(update_order, function (i, value) {
            $.map(value, function (element, index) {
                if (element.user_id == update_user_id) {
                    open_orders = responseData.open_orders;
                    objIndex = open_orders.findIndex(
                        (obj) => obj.id == element.order_id
                    );
                    open_orders[objIndex].amount =
                        parseFloat(open_orders[objIndex].amount) -
                        parseFloat(element.amount);
                    open_orders[objIndex].total =
                        parseFloat(open_orders[objIndex].amount) *
                        parseFloat(open_orders[objIndex].price);

                    obj2 = {
                        price: element.price,
                        status: "Filled",
                        type: element.type,
                        ordertype: element.ordertype,
                        datetime: element.datetime,
                        total: element.total,
                        amount: element.amount,
                    };

                    my_tradehistory = responseData.my_tradehistory;
                    if (my_tradehistory == 0) {
                        var arr = new Array();
                        arr.push(obj2);
                        responseData.my_tradehistory = arr;
                    } else {
                        my_tradehistory.unshift(obj2);
                        responseData.my_tradehistory = my_tradehistory;
                    }
                }
            });
            responseData.open_orders = open_orders;
        });

        open_orders = responseData.open_orders;
        objs = jQuery.grep(open_orders, function (value) {
            return value.amount > 0;
        });
        responseData.open_orders = objs;
        getOpenOrders();
        getMyhistory();
    }
});

function getOpenOrders() {
    open_orders = responseData.open_orders;

    var historys = "";
    if (open_orders != "" && open_orders != 0) {
        open_orders_length = open_orders.length;
        for (count = 0; count < open_orders_length; count++) {
            datetime = open_orders[count].datetime;
            classname = "posVal";
            type = open_orders[count].type;
            type = type.charAt(0).toUpperCase() + type.slice(1);
            if (type == "Sell") classname = "negVal";
            ordertype = open_orders[count].ordertype;
            //amount = open_orders[count].amount;
            amount = removeZero(
                parseFloat(open_orders[count].amount).toFixed(8)
            );
            amount1 = parseFloat(open_orders[count].amount).toFixed(8);
            amount_zero = amount1.match(/[0]+$/);
            if (amount_zero == null) {
                amountzero = "";
            } else if (amount_zero == "00000000") {
                amountzero = "0000000";
            } else {
                amountzero = amount_zero;
            }

            /*if(decvalue == 8) {
              if(amountzero == '00000000') {
                amountzero = '0000000';
              }
            } else if(decvalue == 4) {
              if(amountzero == '0000') {
                amountzero = '000';
              }
            } else if(decvalue == 2) {
               if(amountzero == '00') {
                amountzero = '0';
              }
            } else if(decvalue == 1) {
              if(amountzero == '0') {
                amountzero = '';
              }
            }*/
            if (amount > 0) {
                //total = open_orders[count].total;
                total = removeZero(
                    parseFloat(open_orders[count].total).toFixed(8)
                );
                total1 = parseFloat(open_orders[count].total).toFixed(8);
                total_zero = total1.match(/[0]+$/);
                if (total_zero == null) {
                    totalzero = "";
                } else if (total_zero == "00000000") {
                    totalzero = "0000000";
                } else {
                    totalzero = total_zero;
                }

                /*if(decvalue == 8) {
              if(totalzero == '00000000') {
                totalzero = '0000000';
              }
            } else if(decvalue == 4) {
              if(totalzero == '0000') {
                totalzero = '000';
              }
            } else if(decvalue == 2) {
               if(totalzero == '00') {
                totalzero = '0';
              }
            } else if(decvalue == 1) {
              if(totalzero == '0') {
                totalzero = '';
              }
            }*/
                //price = open_orders[count].price;
                price = removeZero(
                    parseFloat(open_orders[count].price).toFixed(8)
                );
                price1 = parseFloat(open_orders[count].price).toFixed(8);
                price_zero = price1.match(/[0]+$/);
                if (price_zero == null) {
                    pricezero = "";
                } else if (price_zero == "00000000") {
                    pricezero = "0000000";
                } else {
                    pricezero = price_zero;
                }

                /*if(decvalue == 8) {
              if(pricezero == '00000000') {
                pricezero = '0000000';
              }
            } else if(decvalue == 4) {
              if(pricezero == '0000') {
                pricezero = '000';
              }
            } else if(decvalue == 2) {
               if(pricezero == '00') {
                pricezero = '0';
              }
            } else if(decvalue == 1) {
              if(pricezero == '0') {
                pricezero = '';
              }
            }*/

                id = open_orders[count].id;
                onClick = "return cancelOrder('" + id + "')";
                historys =
                    historys +
                    "<tr><td>" +
                    datetime +
                    '</td><td class="' +
                    classname +
                    '">' +
                    type +
                    "</td><td>" +
                    ordertype +
                    "</td><td>" +
                    amount +
                    '<span class="opentrailingzero white">' +
                    amountzero +
                    "</span></td><td>" +
                    price +
                    '<span class="opentrailingzero grey">' +
                    pricezero +
                    "</span></td><td>" +
                    total +
                    '<span class="opentrailingzero white">' +
                    totalzero +
                    '</span></td><td class="text-center"><a href="javascript:;" onclick="' +
                    onClick +
                    '"><span class="fa fa-times-circle time_ic"></span></a></td></tr>';
            }
        }
    }
    if (historys != "") {
        $("#openOrdersTable .mCSB_container").html(historys);
    } else {
        $("#openOrdersTable .mCSB_container").html(
            '<div style="text-align:center;"><span class="noopenorder">' +
                no_data_found +
                "</span></div>"
        );
    }
}

function getMyhistory() {
    my_orders = responseData.my_tradehistory;
    var historys = "";
    if (my_orders != "" && my_orders != 0) {
        my_orders_lenght = my_orders.length;
        for (count = 0; count < my_orders_lenght; count++) {
            //amount = my_orders[count].amount;
            amount = removeZero(parseFloat(my_orders[count].amount).toFixed(8));
            amount1 = parseFloat(my_orders[count].amount).toFixed(8);
            amount_zero = amount1.match(/[0]+$/);
            if (amount_zero == null) {
                amountzero = "";
            } else if (amount_zero == "00000000") {
                amountzero = "0000000";
            } else {
                amountzero = amount_zero;
            }

            /*if(decvalue == 8) {
              if(amountzero == '00000000') {
                amountzero = '0000000';
              }
            } else if(decvalue == 4) {
              if(amountzero == '0000') {
                amountzero = '000';
              }
            } else if(decvalue == 2) {
               if(amountzero == '00') {
                amountzero = '0';
              }
            } else if(decvalue == 1) {
              if(amountzero == '0') {
                amountzero = '';
              }
            }*/

            orderTime = my_orders[count].datetime;
            type = my_orders[count].type;
            ordertype = my_orders[count].ordertype;
            //price = my_orders[count].price;
            price = removeZero(parseFloat(my_orders[count].price).toFixed(8));
            price1 = parseFloat(my_orders[count].price).toFixed(8);
            price_zero = price1.match(/[0]+$/);
            if (price_zero == null) {
                pricezero = "";
            } else if (price_zero == "00000000") {
                pricezero = "0000000";
            } else {
                pricezero = price_zero;
            }

            /*if(decvalue == 8) {
              if(pricezero == '00000000') {
                pricezero = '0000000';
              }
            } else if(decvalue == 4) {
              if(pricezero == '0000') {
                pricezero = '000';
              }
            } else if(decvalue == 2) {
               if(pricezero == '00') {
                pricezero = '0';
              }
            } else if(decvalue == 1) {
              if(pricezero == '0') {
                pricezero = '';
              }
            }*/
            //total = my_orders[count].total;
            total = removeZero(parseFloat(my_orders[count].total).toFixed(8));
            total1 = parseFloat(my_orders[count].total).toFixed(8);
            total_zero = total1.match(/[0]+$/);
            if (total_zero == null) {
                totalzero = "";
            } else if (total_zero == "00000000") {
                totalzero = "0000000";
            } else {
                totalzero = total_zero;
            }

            /*if(decvalue == 8) {
              if(totalzero == '00000000') {
                totalzero = '0000000';
              }
            } else if(decvalue == 4) {
              if(totalzero == '0000') {
                totalzero = '000';
              }
            } else if(decvalue == 2) {
               if(totalzero == '00') {
                totalzero = '0';
              }
            } else if(decvalue == 1) {
              if(totalzero == '0') {
                totalzero = '';
              }
            }*/
            status = my_orders[count].status;
            type = type.charAt(0).toUpperCase() + type.slice(1);
            class_name_type = "text-success";
            class_name_status = "text-success";
            if (type == "Sell") {
                class_name_type = "text-danger";
            }
            if (status == "Cancelled") {
                class_name_status = "text-danger";
            }
            historys =
                historys +
                "<tr><td>" +
                orderTime +
                '</td><td class="' +
                class_name_type +
                '">' +
                type +
                "</td><td>" +
                ordertype +
                "</td><td>" +
                amount +
                '<span class="myordertrailingzero white">' +
                amountzero +
                "</span></td><td>" +
                price +
                '<span class="myordertrailingzero grey">' +
                pricezero +
                "</span></td><td>" +
                total +
                '<span class="myordertrailingzero white">' +
                totalzero +
                '</span></td><td class="text-center ' +
                class_name_status +
                '">' +
                status +
                "</td></tr>";
        }
    }
    if (historys != "") {
        $("#myTradeHistory .mCSB_container").html(historys);
    } else {
        $("#myTradeHistory .mCSB_container").html(
            '<div style="text-align:center;"><span class="noopenorder">' +
                no_data_found +
                "</span></div>"
        );
    }
}

function getStopOrders() {
    stop_orders = responseData.stop_orders;
    var historys = "";
    if (stop_orders != "" && stop_orders != 0) {
        stop_orders_lenght = stop_orders.length;
        for (count = 0; count < stop_orders_lenght; count++) {
            //amount = stop_orders[count].amount;
            amount = removeZero(
                parseFloat(stop_orders[count].amount).toFixed(8)
            );
            amount1 = parseFloat(stop_orders[count].amount).toFixed(8);
            amount_zero = amount1.match(/[0]+$/);
            if (amount_zero == null) {
                amountzero = "";
            } else if (amount_zero == "00000000") {
                amountzero = "0000000";
            } else {
                amountzero = amount_zero;
            }

            /*if(decvalue == 8) {
              if(amountzero == '00000000') {
                amountzero = '0000000';
              }
            } else if(decvalue == 4) {
              if(amountzero == '0000') {
                amountzero = '000';
              }
            } else if(decvalue == 2) {
               if(amountzero == '00') {
                amountzero = '0';
              }
            } else if(decvalue == 1) {
              if(amountzero == '0') {
                amountzero = '';
              }
            }*/

            orderTime = stop_orders[count].datetime;
            type = stop_orders[count].type;
            ordertype = stop_orders[count].ordertype;
            //price = stop_orders[count].price;
            price = removeZero(parseFloat(stop_orders[count].price).toFixed(8));
            price1 = parseFloat(stop_orders[count].price).toFixed(8);
            price_zero = price1.match(/[0]+$/);
            if (price_zero == null) {
                pricezero = "";
            } else if (price_zero == "00000000") {
                pricezero = "0000000";
            } else {
                pricezero = price_zero;
            }

            /*if(decvalue == 8) {
              if(pricezero == '00000000') {
                pricezero = '0000000';
              }
            } else if(decvalue == 4) {
              if(pricezero == '0000') {
                pricezero = '000';
              }
            } else if(decvalue == 2) {
               if(pricezero == '00') {
                pricezero = '0';
              }
            } else if(decvalue == 1) {
              if(pricezero == '0') {
                pricezero = '';
              }
            }*/

            //stopprice = stop_orders[count].stopprice;
            stopprice = removeZero(
                parseFloat(stop_orders[count].stopprice).toFixed(8)
            );
            stopprice1 = parseFloat(stop_orders[count].stopprice).toFixed(8);
            stopprice_zero = stopprice1.match(/[0]+$/);
            if (stopprice_zero == null) {
                stoppricezero = "";
            } else if (stopprice_zero == "00000000") {
                stoppricezero = "0000000";
            } else {
                stoppricezero = stopprice_zero;
            }

            /*if(decvalue == 8) {
              if(stoppricezero == '00000000') {
                stoppricezero = '0000000';
              }
            } else if(decvalue == 4) {
              if(stoppricezero == '0000') {
               stoppricezero = '000';
              }
            } else if(decvalue == 2) {
               if(stoppricezero == '00') {
                stoppricezero = '0';
              }
            } else if(decvalue == 1) {
              if(stoppricezero == '0') {
                stoppricezero = '';
              }
            }*/
            //total = stop_orders[count].total;
            total = removeZero(parseFloat(stop_orders[count].total).toFixed(8));
            total1 = parseFloat(stop_orders[count].total).toFixed(8);
            total_zero = total1.match(/[0]+$/);
            if (total_zero == null) {
                totalzero = "";
            } else if (total_zero == "00000000") {
                totalzero = "0000000";
            } else {
                totalzero = total_zero;
            }

            /*if(decvalue == 8) {
              if(totalzero == '00000000') {
                totalzero = '0000000';
              }
            } else if(decvalue == 4) {
              if(totalzero == '0000') {
                totalzero = '000';
              }
            } else if(decvalue == 2) {
               if(totalzero == '00') {
                totalzero = '0';
              }
            } else if(decvalue == 1) {
              if(totalzero == '0') {
                totalzero = '';
              }
            }*/

            status = stop_orders[count].status;
            type = type.charAt(0).toUpperCase() + type.slice(1);
            class_name_type = "text-success";
            if (type == "Sell") {
                class_name_type = "text-danger";
            }

            id = stop_orders[count].id;
            onClick = "return cancelOrder('" + id + "')";
            historys =
                historys +
                "<tr><td>" +
                orderTime +
                '</td><td class="' +
                class_name_type +
                '">' +
                type +
                "</td><td>" +
                ordertype +
                "</td><td>" +
                amount +
                '<span class="stopordertrailingzero white">' +
                amountzero +
                "</span></td><td>" +
                price +
                '<span class="stopordertrailingzero grey">' +
                pricezero +
                "<span></td><td>" +
                stopprice +
                '<span class="stopordertrailingzero white">' +
                stoppricezero +
                "</span></td><td>" +
                total +
                '<span class="stopordertrailingzero grey">' +
                totalzero +
                '</span></td><td class="text-center"><a href="javascript:;" onclick="' +
                onClick +
                '"><span class="fa fa-times-circle time_ic"></span></a></td></tr>';
        }
    }
    if (historys != "") {
        $("#stopOrdersTable .mCSB_container").html(historys);
    } else {
        $("#stopOrdersTable .mCSB_container").html(
            '<div style="text-align:center;"><span class="noopenorder">' +
                no_data_found +
                "</span></div>"
        );
    }
}
function check_stop_records(active_arr) {
    if (active_arr) {
        active_values = active_arr["active_values"];
        $.each(active_values, function (i, active_values_result) {
            check_type = active_values_result.type;
            newarray = {
                0: {
                    price: active_values_result.price,
                    amount: active_values_result.amount,
                },
            };
            socket.emit("receiveorder", {
                recMsg: "create",
                recPair: pairData.pair,
                new_array: newarray,
                existing_type: "",
                type: check_type,
                existing_array: "",
            });
        });

        stop_orders_sellbuy = active_arr["active"];
        socket.emit("check_stoporder_update", {
            recMsg: "create",
            recPair: pairData.pair,
            stoparr: stop_orders_sellbuy,
        });

        filled_orders = active_arr["filled"];
        if (filled_orders) {
            filled_orders = filled_orders[0];
            responseData.update_active_order = 0;
            in_status = status;
            $.each(filled_orders, function (i, value) {
                if (value) {
                    in_status = 1;
                    var hist = "";
                    newarray = value.new_array;
                    datetime = value.datetime;
                    update_type = "";
                    type = value.type;
                    if (value.orders == 1) {
                        update_type = value.existing_type;
                        hist = value.tradehistory;
                        stop_orders = value.stop_orders;
                        existing_array = value.existing_array;
                        socket.emit("receiveorder", {
                            recMsg: "create",
                            recPair: pairData.pair,
                            new_array: newarray,
                            existing_type: update_type,
                            type: type,
                            existing_array: existing_array,
                        });
                        socket.emit("receiverequest", {
                            recMsg: "create",
                            recPair: pairData.pair,
                            recent_trade_history: hist,
                        });
                    } else {
                        socket.emit("receiveorder", {
                            recMsg: "create",
                            recPair: pairData.pair,
                            new_array: newarray,
                            existing_type: "",
                            type: type,
                            existing_array: "",
                        });
                    }
                    active_order_arr = value.active_order;
                    $.each(active_order_arr, function (i, active_order) {
                        update_user_ids = active_order.user_id;
                        active_obj = {
                            update_type: value.active,
                            price: active_order.price,
                            total: active_order.total,
                            type: active_order.type,
                            status: "Filled",
                            ordertype: active_order.ordertype,
                            datetime: active_order.datetime,
                            amount: active_order.amount,
                            id: active_order.id,
                        };
                        var obj_update = {};
                        obj_update[update_user_ids] = active_obj;
                        active_obj = obj_update;
                        //console.log(active_obj);
                        //console.log('===' + i);
                        update_active_order = responseData.update_active_order;
                        if (update_active_order == 0) {
                            var arr = new Array();
                            arr.push(active_obj);
                            responseData.update_active_order = arr;
                        } else {
                            update_active_order.unshift(active_obj);
                            responseData.update_active_order =
                                update_active_order;
                        }
                    });

                    socket.emit("receiveupdate_active_order", {
                        recMsg: "create",
                        recPair: pairData.pair,
                        update_active_order: responseData.update_active_order,
                    });
                    if (stop_orders) {
                        check_stop_records(stop_orders);
                    }
                }
            });
        }
    }
}

$("#probuy").click(function () {
    notif({
        msg:
            '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
            profile_error +
            " ",
        type: "error",
    });
    setTimeout(function () {
        window.location.href = siteurl + "/dashboard";
    }, 1500);
});

$("#prosell").click(function () {
    notif({
        msg:
            '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
            profile_error +
            " ",
        type: "error",
    });
    setTimeout(function () {
        window.location.href = siteurl + "/dashboard";
    }, 1500);
});

socket.on("trade_updates_price_single_bin", function (res) {
    values = res.msg;
    name = values.s;
    name1 = values.pairr;
    price = pairData.lastPrice = parseFloat(values.c);
    if (name1 == symbolset) {
        cls = values.c;
        opn = values.o;
        $(".volume").html(values.q);
        if (cls < opn) {
            $(".change").addClass("text-danger");
            $(".change").removeClass("text-success");
            $("#change_" + name1).addClass("text-danger");
            $("#change_" + name1).removeClass("text-success");
        } else {
            $(".change").addClass("text-success");
            $(".change").removeClass("text-danger");
            $("#change_" + name1).addClass("text-success");
            $("#change_" + name1).removeClass("text-danger");
        }
        $(".buyprice").val();
        $(".sellprice").val();
        $(".lastprice").html(price);
        $(".high").html(values.h);
        $(".low").html(values.l);

        perc = values.P;
        perc = parseFloat(perc).toFixed(2);
        $(".change").html(perc + "%");
        $("#change_" + name1).html(perc);
        $("#volume_" + name1).html(values.q);
        $("#last_price_" + name1).html(price);
    } else {
        cls = values.c;
        opn = values.o;
        $("#volume_" + name1).html(values.q);
        if (cls < opn) {
            $("#change_" + name1).addClass("text-danger");
            $("#change_" + name1).removeClass("text-success");
        } else {
            $("#change_" + name1).addClass("text-success");
            $("#change_" + name1).removeClass("text-danger");
        }

        $("#last_price_" + name1).html(price);
        perc = values.P;
        perc = parseFloat(perc).toFixed(2);
        $("#change_" + name1).html(perc);
    }
});

function getSocketTicker(pair) {
    socket.emit("24Ticker", pair.replace("_", ""));
    socket.on("24Ticker", function (response) {
        let price = (pairData.lastPrice = parseFloat(response.close));
        let changePercent = parseFloat(response.percentChange).toFixed(2);
        $(".volume").html(response.volume);
        $(".buyprice").val();
        $(".sellprice").val();
        $(".lastprice").html(price);
        $(".high").html(response.high);
        $(".low").html(response.low);

        if (changePercent > 0) {
            $(".change").addClass("text-success");
            $(".change").removeClass("text-danger");
        } else {
            $(".change").removeClass("text-danger");
            $(".change").addClass("text-success");
        }

        $(".change").html(changePercent + "%");
    });
}
