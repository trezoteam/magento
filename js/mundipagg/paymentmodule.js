var toTokenApi = {
    "card": {
        "type": "credit",
        "number": '',
        "holder_name": '',
        "exp_month": '',
        "exp_year": '',
        "cvv": ''
    }
};

function getFormData() {
    toTokenApi.card.holder_name = document.getElementById('mundicheckout-holdername').value;
    toTokenApi.card.number = document.getElementById('mundicheckout-number').value;
    toTokenApi.card.exp_month = document.getElementById('mundicheckout-expmonth').value;
    toTokenApi.card.exp_year = document.getElementById('mundicheckout-expyear').value;
    toTokenApi.card.cvv = document.getElementById('mundicheckout-cvv').value;
}

function createToken(url, data, callback) {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

    xhr.open('POST', url);
    xhr.setRequestHeader("content-type", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState > 3 && xhr.status == 200) {
            callback(JSON.parse(xhr.responseText));
        }
    };

    xhr.send(JSON.stringify(data));

    return xhr;
}

function getCreditCardToken(pkKey, callback) {
    createToken(
        'https://api.mundipagg.com/core/v1/tokens?appId=' + pkKey,
        toTokenApi,
        callback
    );
}

Payment.prototype.save = Payment.prototype.save.wrap(function(save) {
    var key = document.getElementById('tokenDiv').getAttribute('data-mundicheckout-app-id');

    getCreditCardToken(key, function(response) {
        var tokenElement = document.getElementById('mundicheckout-token');

        tokenElement.value = response.id;;
        save();
    });
});
