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

function getFormData(elementIdSuffix) {
    var suffix = typeof elementIdSuffix !== 'undefined' ?
        elementIdSuffix : '';
    toTokenApi.card = {
        type: "credit",
        holder_name: clearHolderName(document.getElementById('mundicheckout-holdername' + suffix)),
        number: clearCardNumber(document.getElementById('mundicheckout-number' + suffix)),
        exp_month: document.getElementById('mundicheckout-expmonth' + suffix).value,
        exp_year: document.getElementById('mundicheckout-expyear' + suffix).value,
        cvv: clearCvv(document.getElementById('mundicheckout-cvv' + suffix))
    };
}

/**
 * Call API
 * @param url string
 * @param data
 * @returns {XMLHttpRequest}
 */
function apiRequest(url, data, callback, method, json,callbackArgsObj) {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open(method, url);

    if (json) {
        xhr.setRequestHeader("content-type", "application/json");
    }

    xhr.onreadystatechange = function () {
        if (xhr.readyState > 3 && xhr.status == 200) {
            callback(JSON.parse(xhr.responseText),callbackArgsObj);
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
function getBrand(creditCardNumber,elementIdSuffix,value) {
    var suffix = '';
    if (typeof elementIdSuffix !== 'undefined') {
       suffix = elementIdSuffix;
    }
    brandName = jQuery("#mundipaggBrandName" + suffix).val();

    if (creditCardNumber.length > 5 &&
        (brandName == "" || typeof value !== 'undefined')) {
        bin = creditCardNumber.substring(0, 6);
        apiRequest(
            "https://api.mundipagg.com/bin/v1/" + bin,
            "",
            fillBrandData,
            "GET",
            false,
            {
                elementIdSuffix : elementIdSuffix,
                installmentsBaseValue: value
            }
        );
    }
    if (creditCardNumber.length < 6) {
        clearBrand(elementIdSuffix);
    }
}

function fillBrandData(data,argsObj) {
    if (data.brand != "" && data.brand != undefined) {
        var suffix = undefined;
        if (
            typeof argsObj !== 'undefined' &&
            typeof argsObj.elementIdSuffix !== 'undefined'
        ) {
            suffix = argsObj.elementIdSuffix;
        }
        showBrandImage(data.brand,suffix);
        getInstallments(jQuery("#baseUrl").val(), data.brandName,argsObj);
        suffix = typeof suffix !== 'undefined' ? suffix : '';
        console.log("#mundipagg_creditcard_brand_name" + suffix, jQuery("#mundipagg_creditcard_brand_name" + suffix));
        jQuery("#mundipagg_creditcard_brand_name" + suffix).val(data.brandName);
    }
}

/**
 * Show credit card brand image
 * @param brand
 */
function showBrandImage(brandName,elementIdSuffix) {
    html = "<img src='https://dashboard.mundipagg.com/emb/images/brands/" + brandName + ".jpg' ";
    html += " class='mundipaggImage' width='26'>";

    var suffix = '';
    if (typeof elementIdSuffix !== 'undefined') {
        suffix = elementIdSuffix;
    }

    jQuery("#mundipaggBrandName" + suffix).val(brandName);
    jQuery("#mundipaggBrandImage" + suffix).html(html);
}

function clearBrand(elementIdSuffix){
    var suffix = typeof elementIdSuffix !== 'undefined' ?
        elementIdSuffix : '';
    jQuery("#mundipaggBrandName" + suffix).val("");
    jQuery("#mundipaggBrandImage" + suffix).html("");
    jQuery("#mundicheckout-creditCard-installments" + suffix).html("");
    jQuery("#mundipagg_creditcard_brand" + suffix).val("");
}

function getInstallments(baseUrl, brandName,argsObj) {
    var value = '';
    if (typeof argsObj.installmentsBaseValue !== 'undefined') {
        var tmp = parseFloat(argsObj.installmentsBaseValue.replace(',','.'));
        value = '?value=' + tmp;
    }
    apiRequest(
        baseUrl + '/mundipagg/creditcard/getinstallments/' + brandName + value,
        '',
        switchInstallments,
        "GET",
        false,
        argsObj
    );
}

function switchInstallments(data,argsObj) {
    if (data){
        var suffix = '';
        if (typeof argsObj.elementIdSuffix !== undefined) {
            suffix = argsObj.elementIdSuffix;
        }
        html = "<option>1x sem juros</option>";
        jQuery("#mundicheckout-creditCard-installments" + suffix).html("");

        data.forEach(fillInstallments,argsObj);
    }
}

function fillInstallments(item, index) {
    if (item.interest == 0) {
        item.interest = " sem juros";
    } else{
        item.interest = " com " + item.interest + "% de juros";
    }

    html = "<option value='"+item.times+"'>" +
        item.times + "x de " + item.amount + item.interest + "</option>";

    var suffix = typeof this.elementIdSuffix !== 'undefined' ?
        this.elementIdSuffix : '';

    jQuery("#mundicheckout-creditCard-installments" + suffix).append(html);
}

function balanceValues(grandTotal,triggerInput,balanceInputId) {
    var triggerValue = parseFloat(triggerInput.value.replace(',','.'));
    triggerValue = Math.abs(triggerValue);
    triggerValue = Math.round(triggerValue * 100) / 100
    triggerValue = triggerValue > grandTotal ? grandTotal : triggerValue;

    var balanceValue = grandTotal - triggerValue;
    balanceValue = Math.round(balanceValue * 100) / 100

    jQuery("#" + balanceInputId).val((balanceValue + '').replace('.',','));
    jQuery("#" + triggerInput.id).val((triggerValue + '').replace('.',','));

    jQuery(".balanceCC").change();
}