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

function getCurrentYear() {
    var date = new Date();
    return date.getFullYear();
}

/**
 * Get credit card brand
 * @param int creditCardNumber
 */
function getBrand(creditCardNumber) {

    brandName = jQuery("#mundipaggBrandName").val();

    if (creditCardNumber.length > 5 && brandName == "") {
        bin = creditCardNumber.substring(0, 6);
        apiRequest(
            "https://api.mundipagg.com/bin/v1/" + bin,
            "",
            fillBrandData,
            "GET",
            false
        )

    }
    if (creditCardNumber.length < 6) {

        clearBrand();
    }
}

function fillBrandData(data) {
    if (data.brand != "" && data.brand != undefined) {
        showBrandImage(data.brand);
        getInstallments(jQuery("#baseUrl").val(), data.brandName);
    }
}

/**
 * Show credit card brand image
 * @param brand
 */
function showBrandImage(brandName) {
    html = "<img src='https://dashboard.mundipagg.com/emb/images/brands/" + brandName + ".jpg' ";
    html += " class='mundipaggImage' width='26'>";

    jQuery("#mundipaggBrandName").val(brandName);
    jQuery("#mundipaggBrandImage").html(html);
}

function clearBrand(){
    jQuery("#mundipaggBrandName").val("");
    jQuery("#mundipaggBrandImage").html("");
    jQuery("#mundicheckout-creditCard-installments").html("");
}

function getInstallments(baseUrl, brandName) {
    apiRequest(
        baseUrl + '/mundipagg/creditcard/getinstallments/' + brandName,
        '',
        switchInstallments,
        "GET",
        false
    );

}

function switchInstallments(data) {
    if (data){
        html = "<option>1x sem juros</option>";
        jQuery("#mundicheckout-creditCard-installments").html("");

        data.forEach(fillInstallments);
    }
}

function fillInstallments(item, index) {

    if (item.interest == 0) {
        item.interest = " sem juros";
    } else{
        item.interest = " com " + item.interest + "% de juros";
    }

    html = "<option>" + item.times + "x de " + item.amount + item.interest + "</option>";

    jQuery("#mundicheckout-creditCard-installments").append(html);

}
