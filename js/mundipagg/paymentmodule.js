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

var brandName = false;

function getFormData() {
    toTokenApi.card.holder_name = clearHolderName(document.getElementById('mundicheckout-holdername'));
    toTokenApi.card.number = clearCardNumber(document.getElementById('mundicheckout-number'));
    toTokenApi.card.exp_month = document.getElementById('mundicheckout-expmonth').value;
    toTokenApi.card.exp_year = document.getElementById('mundicheckout-expyear').value;
    toTokenApi.card.cvv = clearCvv(document.getElementById('mundicheckout-cvv'));
}

/**
 * Call API
 * @param url string
 * @param data
 * @returns {XMLHttpRequest}
 */
function apiRequest(url, data, callback, method, json) {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open(method, url);

    if (json) {
        xhr.setRequestHeader("content-type", "application/json");
    }

    xhr.onreadystatechange = function () {
        if (xhr.readyState > 3 && xhr.status == 200) {
            callback(JSON.parse(xhr.responseText));
        }else{
            callback(false);
        }
    };

    xhr.send(JSON.stringify(data));

    return xhr;
}

function getCreditCardToken(pkKey, callback) {
    if(validateCreditCardData()){
        apiRequest(
            'https://api.mundipagg.com/core/v1/tokens?appId=' + pkKey,
            toTokenApi,
            callback,
            "POST",
            true
        );
    }
    return false;
}

/**
 * Validate input data
 * @returns {boolean}
 */
function validateCreditCardData() {
    if(
        toTokenApi.card.number.length > 15 &&
        toTokenApi.card.number.length < 22 &&
        isValidBrand() &&
        toTokenApi.card.holder_name.length > 2 &&
        toTokenApi.card.holder_name.length < 51 &&
        toTokenApi.card.exp_month > 0 &&
        toTokenApi.card.exp_month < 13 &&
        toTokenApi.card.exp_year >= getCurrentYear() &&
        toTokenApi.card.cvv.length > 2 &&
        toTokenApi.card.cvv.length < 5
    ){
        return true;
    }else{

        return false;
    }
}

function isValidBrand() {
    return true;
}

function getCurrentYear() {
    var date = new Date();
    return date.getFullYear();
}

/**
 * Get credit card brand
 * @param int creditCardNumber
 */
function getBrand(creditCardNumber) {
    if (creditCardNumber.length > 5 && !brandName) {
        bin = creditCardNumber.substring(0, 6);
        apiRequest(
            "https://api.mundipagg.com/bin/v1/" + bin,
            "",
            showBrandImage,
            "GET",
            false
        )

    }
    if (creditCardNumber.length < 5 && brandName) {
        console.log("limpou");
        brandName = false;
    }
}

/**
 * Show credit card brand image
 * @param brand
 */
function showBrandImage(data) {
    if (data.brand) {
        console.log(data.brand);
        brandName = data.brand;
    }
}