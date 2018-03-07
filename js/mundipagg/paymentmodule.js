var toTokenApi = {};

var brandName = false;
/*
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
*/
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

function getCreditCardToken(pkKey,elementId, callback) {
    if(validateCreditCardData(elementId)){
        apiRequest(
            'https://api.mundipagg.com/core/v1/tokens?appId=' + pkKey,
            toTokenApi[elementId],
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
function validateCreditCardData(elementId) {
    if(
        toTokenApi[elementId].card.number.length > 15 &&
        toTokenApi[elementId].card.number.length < 22 &&
        toTokenApi[elementId].card.holder_name.length > 2 &&
        toTokenApi[elementId].card.holder_name.length < 51 &&
        toTokenApi[elementId].card.exp_month > 0 &&
        toTokenApi[elementId].card.exp_month < 13 &&
        toTokenApi[elementId].card.exp_year >= getCurrentYear() &&
        toTokenApi[elementId].card.cvv.length > 2 &&
        toTokenApi[elementId].card.cvv.length < 5
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
/*function getBrand(creditCardNumber,elementIdSuffix,value) {
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
}*/

/*function fillBrandData(data,argsObj) {
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
        jQuery("#mundipagg_creditcard_brand_name" + suffix).val(data.brandName);
    }
}*/

/**
 * Show credit card brand image
 * @param brand
 */
/*function showBrandImage(brandName,elementIdSuffix) {
    html = "<img src='https://dashboard.mundipagg.com/emb/images/brands/" + brandName + ".jpg' ";
    html += " class='mundipaggImage' width='26'>";

    var suffix = '';
    if (typeof elementIdSuffix !== 'undefined') {
        suffix = elementIdSuffix;
    }

    jQuery("#mundipaggBrandName" + suffix).val(brandName);
    jQuery("#mundipaggBrandImage" + suffix).html(html);
}*/

/*function clearBrand(elementIdSuffix){
    var suffix = typeof elementIdSuffix !== 'undefined' ?
        elementIdSuffix : '';
    jQuery("#mundipaggBrandName" + suffix).val("");
    jQuery("#mundipaggBrandImage" + suffix).html("");
    jQuery("#mundicheckout-creditCard-installments" + suffix).html("");
    jQuery("#mundipagg_creditcard_brand" + suffix).val("");
}*/

/*function getInstallments(baseUrl, brandName,argsObj) {
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
}*/

/*function switchInstallments(data,argsObj) {
    if (data){
        var suffix = '';
        if (typeof argsObj.elementIdSuffix !== undefined) {
            suffix = argsObj.elementIdSuffix;
        }
        html = "<option>1x sem juros</option>";
        jQuery("#mundicheckout-creditCard-installments" + suffix).html("");

        data.forEach(fillInstallments,argsObj);
    }
}*/

/*function fillInstallments(item, index) {
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
}*/

function balanceValues(grandTotal,triggerInput,balanceInputId) {
    var triggerValue = parseFloat(triggerInput.value.replace(',','.'));
    if(isNaN(triggerValue)) {
        triggerValue = 0;
    }

    triggerValue = Math.abs(triggerValue);
    triggerValue = Math.round(triggerValue * 100) / 100
    triggerValue = triggerValue > grandTotal ? grandTotal : triggerValue;
    triggerValue = triggerValue.toFixed(2);

    var balanceValue = grandTotal - triggerValue;
    balanceValue = (Math.round(balanceValue * 100) / 100).toFixed(2);

    jQuery("#" + balanceInputId).val(balanceValue);
    jQuery("#" + triggerInput.id).val(triggerValue);

    jQuery(".balanceCC").change();
}

////////////////////////new architecture


//validations
function initPaymentMethod(methodCode)
{
    Validation.add(methodCode + '_boleto_validate-mundipagg-cpf', 'CPF inválido', function(cpf) {
        return validateCPF(cpf);
    });

    Validation.add(methodCode + '_creditcard_validate-mundipagg-creditcard-exp', 'Data inválida', function(v,element) {
        var triggerId = element.id;
        var elementIndex = triggerId
            .replace(methodCode + '_creditcard_','')
            .replace('_mundicheckout-expiration-date','');
        var elementId = methodCode + "_creditcard_"+ elementIndex;

        var month = document.getElementById(elementId + '_mundicheckout-expmonth');
        var year = document.getElementById(elementId + '_mundicheckout-expyear');
        return validateCreditCardExpiration(year.value, month.value);
    });

    Payment.prototype.save = Payment.prototype.save.wrap(function(save) {
        var hasCardInfo = false;
        var creditCardTokenDiv = '.'  + methodCode + "_creditcard_tokenDiv";
        //generate token check table and check if cardInfos exists;
        var tokenCheckTable = {};

        jQuery(creditCardTokenDiv).each(function(index, element) {
            tokenCheckTable[element.id] = false;
            hasCardInfo = true;

        });
        if(this.currentMethod !== methodCode || hasCardInfo === false) {
            return save();
        }
        var prototypeWrapper = this;

        //for each of creditcard forms
        jQuery('.' +methodCode+ "_creditcard_tokenDiv").each(function(index,element) {
            var elementId = element.id.replace('_tokenDiv', '');
            var key = document.getElementById(element.id)
                .getAttribute('data-mundicheckout-app-id');
            var tokenElement = document.getElementById(elementId + '_mundicheckout-token');
            var validator = new Validation(prototypeWrapper.form);
            if (prototypeWrapper.validate() && validator.validate()) {
                getCreditCardToken(key, elementId, function (response) {
                    if (response != false) {
                        tokenElement.value = response.id;
                        jQuery("#"+elementId+"_mundipagg-invalid-credit-card").hide();
                        tokenCheckTable[element.id] = true;
                        //check if all tokens are generated.
                        var canSave = true;
                        jQuery('.' +methodCode+ "_creditcard_tokenDiv").each(function(index,element) {
                            if (tokenCheckTable[element.id] === false) {
                                canSave = false;
                            }
                        });
                        if (canSave) {
                            save();
                        }
                        return;
                    }
                    tokenElement.value = "";
                    jQuery("#"+elementId+"_mundipagg-invalid-credit-card").show();
                });
            }
        });
    });
}

function validateCPF(cpf)
{
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11)
        return false;
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1))
        {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais)
    {
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
            soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--)
            soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}

function validateCreditCardExpiration(year, month) {
    var date = new Date();
    var expDate = new Date(year, month - 1, 1);
    var today = new Date(date.getFullYear(), date.getMonth(), 1);
    if (expDate < today) {
        return false;
    }
    return true;
}

//form data
function getFormData(elementId) {

    if (typeof toTokenApi[elementId] === 'undefined') {
        toTokenApi[elementId] = { card:{} };
    }
    toTokenApi[elementId].card = {
        type: "credit",
        holder_name: clearHolderName(document.getElementById(elementId+'_mundicheckout-holdername')),
        number: clearCardNumber(document.getElementById(elementId+'_mundicheckout-number')),
        exp_month: document.getElementById(elementId+'_mundicheckout-expmonth').value,
        exp_year: document.getElementById(elementId+'_mundicheckout-expyear').value,
        cvv: clearCvv(document.getElementById(elementId+'_mundicheckout-cvv'))
    };
}

function getBrand(elementId) {

    var brandName = jQuery("#" + elementId +"_mundipaggBrandName").val();
    var creditCardNumber = jQuery("#" + elementId +"_mundicheckout-number").val();
    var value = jQuery("#" + elementId +"_value").val();

    if (
        creditCardNumber.length > 5 &&
        (brandName === "" || typeof value !== 'undefined')
    ) {
        var bin = creditCardNumber.substring(0, 6);
        apiRequest(
            "https://api.mundipagg.com/bin/v1/" + bin,
            "",
            fillBrandData,
            "GET",
            false,
            {
                elementId : elementId,
                installmentsBaseValue: value
            }
        );
    }
    if (creditCardNumber.length < 6) {
        clearBrand(elementId);
    }
}

function fillBrandData(data,argsObj) {
    if (data.brand != "" && data.brand != undefined) {
        showBrandImage(data.brand,argsObj.elementId);
        getInstallments(jQuery("#baseUrl").val(), data.brandName,argsObj);
        jQuery("#"+argsObj.elementId+"_brand_name").val(data.brandName);
    }
}

function clearBrand(elementId){
    jQuery("#"+elementId+"_mundipaggBrandName").val("");
    jQuery("#"+elementId+"_mundipaggBrandImage").html("");
    jQuery("#"+elementId+"_mundicheckout-creditCard-installments").html("");
    jQuery("#"+elementId+"_mundipagg_creditcard_brand").val(""); //@fixme Is this really necessary?
}

function showBrandImage(brandName,elementId) {
    var html = "<img src='https://dashboard.mundipagg.com/emb/images/brands/" + brandName + ".jpg' ";
    html += " class='mundipaggImage' width='26'>";

    jQuery("#"+elementId+"_mundipaggBrandName").val(brandName);
    jQuery("#"+elementId+"_mundipaggBrandImage" ).html(html);
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
        var html = "<option>1x sem juros</option>";
        jQuery("#"+argsObj.elementId+"_mundicheckout-creditCard-installments").html("");

        data.forEach(fillInstallments,argsObj);
    }
}

function fillInstallments(item) {
    if (item.interest == 0) {
        item.interest = " sem juros";
    } else{
        item.interest = " com " + item.interest + "% de juros";
    }

    var html = "<option value='"+item.times+"'>" +
        item.times + "x de " + item.amount + item.interest + "</option>";

    jQuery("#"+this.elementId+"_mundicheckout-creditCard-installments").append(html);
}






