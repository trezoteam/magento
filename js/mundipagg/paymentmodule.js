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

    callGetCreditCardToken();
}

function callGetCreditCardToken() {

    if(validateCreditCardData()){
        var key = document.getElementById('tokenDiv').getAttribute('data-mundicheckout-app-id');
        getCreditCardToken(key);
    }
}

/**
 * Call API
 * @param url string
 * @param data
 * @returns {XMLHttpRequest}
 */
function apiRequest(url, data) {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

    xhr.open('POST', url);
    xhr.setRequestHeader("content-type", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState > 3 && xhr.status == 200) {
            setTokenToInput(JSON.parse(xhr.responseText));
        }
    };

    xhr.send(JSON.stringify(data));
    return xhr;
}

function setTokenToInput(apiResponse) {
    console.log(apiResponse);
    var tokenElement = document.getElementById('mundicheckout-token');
    tokenElement.value = apiResponse.id;
}

function getCreditCardToken(pkKey) {
    apiRequest(
        'https://api.mundipagg.com/core/v1/tokens?appId=' + pkKey,
        toTokenApi
    );
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
