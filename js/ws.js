function socketCall(streams, actionType = "header") {
    let binanceSocket = new WebSocket(
        "wss://stream.binance.com/ws/" + streams.join("/")
    );

    // when message received from web socket then
    binanceSocket.onmessage = function (event) {
        try {
            let msgs = JSON.parse(event.data);
            if (Array.isArray(msgs)) {
                for (let msg of msgs) {
                    actionType === "header"
                        ? handleHeaderData(msg)
                        : handleMarketData(msg);
                }
            } else {
                actionType === "header"
                    ? handleHeaderData(msgs)
                    : handleMarketData(msgs);
            }
        } catch (e) {
            console.log("Unknown message: " + event.data, e);
        }
    };

    binanceSocket.onclose = function () {
        console.log("Binance disconnected");
    };
}

function handleHeaderData(msg) {
    let id = msg.s;
    let pricePercentage = parseFloat(msg.P).toFixed(2);
    let tvolume = numberFormatConversion(parseFloat(msg.q).toFixed(2));
    $("body")
        .find(`#ws-${id.toLowerCase()}-lprice`)
        .html("&#8377;" + currencyFormat(msg.a));
    $("body").find(`#ws-${id.toLowerCase()}-per-price`).html(pricePercentage);
    $("body")
        .find(`#ws-${id.toLowerCase()}-per-price`)
        .attr("class", pricePercentage > 0 ? "text-success" : "text-danger");
    $("body").find(`#ws-${id.toLowerCase()}-tvolume`).html(tvolume);
    $("body")
        .find(`#ws-${id.toLowerCase()}-cprice`)
        .html("&#8377;" + currencyFormat(msg.c));
}

function handleMarketData(klineData) {
    let data = klineData.k;
    let selector = data.s;
    let id = selector.toLowerCase();
    let baseAsset = selector.replace("USDT", "");
    let closePrice = currencyFormat(data.c);
    let tvolume = numberFormatConversion(parseFloat(data.q).toFixed(2));
    let highPrice = currencyFormat(data.h);
    let lowPrice = currencyFormat(data.l);
    let marketCap = parseFloat(data.q) * parseFloat(closePrice);
    let rowSelector = $("body").find(`#js-${id}-market`);
    if (rowSelector.length) {
        $("body")
            .find(`#js-${id}-market-closePrice`)
            .html("&#8377;" + closePrice);
        $("body")
            .find(`#js-${id}-market-highPrice`)
            .html("&#8377;" + highPrice);
        $("body")
            .find(`#js-${id}-market-lowPrice`)
            .html("&#8377;" + lowPrice);
        $("body").find(`#js-${id}-market-tvolume`).html(tvolume);
        $("body")
            .find(`#js-${id}-market-marketcap`)
            .html("&#8377;" + marketCap);
    } else {
        $("body").find(`#js-loader`).remove();
        let rowTdHtml = `<tr id="js-${id}-market">`;
        rowTdHtml += `<td><span class="slstar"><i class="fa fa-star-o"></i> </span></td>`;
        rowTdHtml += `<td><img class="tabstic" src="img/coins/${baseAsset.toLowerCase()}.png" alt="BitByBTC-${baseAsset}"><b><span>${baseAsset}</span></b></td>`;
        rowTdHtml += `<td><i id="js-${id}-market-closePrice">&#8377;${closePrice} INR</td>`;
        rowTdHtml += `<td><i id="js-${id}-market-highPrice">&#8377;${highPrice}</i></td>`;
        rowTdHtml += `<td><i id="js-${id}-market-lowPrice">&#8377;${lowPrice}</i></td>`;
        rowTdHtml += `<td><i id="js-${id}-market-tvolume">${tvolume}</i></td>`;
        rowTdHtml += `<td><i id="js-${id}-market-marketcap">&#8377;${marketCap}</i></td></tr>`;
        $("body").find(`#js-btc-market`).append(rowTdHtml);
    }
}

function numberFormatConversion(nStr) {
    nStr += "";
    x = nStr.split(".");
    x1 = x[0];
    x2 = x.length > 1 ? "." + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "," + "$2");
    }
    return x1 + x2;
}

function currencyFormat(dataPrice) {
    let inrPrice = 73.67;
    let convPrice = dataPrice * inrPrice;
    let price = new Intl.NumberFormat("en-IN", {
        maximumSignificantDigits: 3,
        currency: "INR",
    }).format(parseFloat(convPrice).toFixed(8));
    return price;
}

let streams = ["bttusdt@ticker", "bnbbusd@ticker", "ethusdt@ticker"];
let maketStreams = [
    "btcusdt@kline_5m",
    "ethusdt@kline_5m",
    "bnbusdt@kline_5m",
    "ltcusdt@kline_5m",
    "trxusdt@kline_5m",
    "bttusdt@kline_5m",
    "shibusdt@kline_5m",
    "dogeusdt@kline_5m",
];

socketCall(streams, "header");
socketCall(maketStreams, "market");
