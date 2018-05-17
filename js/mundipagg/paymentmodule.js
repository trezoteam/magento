var MundiPagg = {};

MundiPagg.init = function(posInitializationCallback)
{
    MundiPagg.Locale.init(posInitializationCallback);
};

MundiPagg.Locale = {
    translactionTable: false,
    init: function (posInitializationCallback)
    {
        if (!this.translactionTable) {
            var baseUrl = '';
            var url = baseUrl + '/mp-paymentmodule/i18n/getTable';
            apiRequest(url,'',function(data){
                if(data !== false) {
                    this.translactionTable = data;
                    posInitializationCallback();
                }
            }.bind(this));
        }
    },
    getTranslaction: function (text)
    {
        var translaction = this.translactionTable[text];
        if (typeof translaction === 'undefined') {
            translaction = text;
        }
        return translaction;
    }
};

var toTokenApi = {};

var brandName = false;

function initSavedCreditCardInstallments() {
    jQuery(".savedCreditCardSelect").each(function () {
        var elementId = jQuery(this).attr("elementId");
        fillSavedCreditCardInstallments(elementId)
    });
}

function fillSavedCreditCardInstallments(elementId) {
    var brandName = jQuery("#" + elementId + "_mundicheckout-SavedCreditCard")
        .children("option:selected")
        .attr("data-brand");
    var baseUrl = jQuery("#baseUrl").val();
    var value = jQuery("#" + elementId + "_value").val();

    var argsObj = {
        elementId: elementId,
        installmentsBaseValue: value
    };

    var fillCardValue = MundiPagg.Locale.getTranslaction('Fill the value for this card');
    var fillCardNumber = MundiPagg.Locale.getTranslaction('Fill the card number');

    var html = '';
    if(brandName == "") {
        html = "<option value=''>"+fillCardNumber+"</option>";
    }
    if(value == "") {
        html = "<option value=''>"+fillCardValue+"</option>";
    }
    if (html !== '') {
        jQuery("#"+argsObj.elementId+"_mundicheckout-creditCard-installments").html(html);
        return;
    }
    getInstallments(baseUrl, brandName, argsObj);
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
        toTokenApi[elementId].card.number.length > 14 &&
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

//validations
function initPaymentMethod(methodCode,orderTotal)
{
    MundiPagg.init(function(){
        initSavedCreditCardInstallments();
        Validation.add(
            methodCode + '_boleto_validate-mundipagg-cpf',
            MundiPagg.Locale.getTranslaction('Invalid CPF'),
            function(cpf) {
                return validateCPF(cpf);
            }
        );

        Validation.add(
            methodCode + '_creditcard_validate-mundipagg-creditcard-exp',
            MundiPagg.Locale.getTranslaction('Invalid Date'),
            function(v,element) {
                var triggerId = element.id;
                var elementIndex = triggerId
                    .replace(methodCode + '_creditcard_','')
                    .replace('_mundicheckout-expiration-date','');
                var elementId = methodCode + "_creditcard_"+ elementIndex;

                if (!isNewCard(elementId)) {
                    return true;
                }

                var month = document.getElementById(elementId + '_mundicheckout-expmonth');
                var year = document.getElementById(elementId + '_mundicheckout-expyear');
                return validateCreditCardExpiration(year.value, month.value);
            }
        );


        if (typeof OSCForm !== 'undefined' ) {
            OSCForm.placeOrderButton.stopObserving('click');
            OSCForm.placeOrderButton.observe('click',function(){

                if (OSCForm.validate()) {
                    console.log('disparar geração de token');
                    OSCForm.placeOrder();
                }
            });
        }
        else {
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

                //foreach value input of the paymentMethod
                //update input balance values
                jQuery('#payment_form_' + methodCode)
                    .find('.multipayment-value-input')
                    .each(
                        function(index,element)
                        {
                            jQuery(element).change();
                        }
                    );

                //for each of creditcard forms
                jQuery('.' +methodCode+ "_creditcard_tokenDiv").each(function(index,element) {
                    var elementId = element.id.replace('_tokenDiv', '');

                    if (isNewCard(elementId) ) {
                        var key = document.getElementById(element.id)
                            .getAttribute('data-mundicheckout-app-id');
                        var tokenElement = document.getElementById(elementId + '_mundicheckout-token');
                        var validator = new Validation(prototypeWrapper.form);
                        if (prototypeWrapper.validate() && validator.validate()) {
                            getCreditCardToken(key, elementId, function (response) {
                                if (response != false) {
                                    tokenElement.value = response.id;
                                    jQuery("#"+elementId+"_mundipagg-invalid-credit-card").hide();
                                    jQuery("#"+elementId+"_brand_name").val(response.card.brand);
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
                        return;
                    }

                    tokenCheckTable[element.id] = true;
                    return save();
                });
            });
        }

        //value balance
        var amountInputs = jQuery('#payment_form_' + methodCode).find('.multipayment-value-input');

        //distribute amount through amount inputs;
        if (amountInputs.length > 1) {
            var distributedAmount = parseFloat(orderTotal);
            distributedAmount /= amountInputs.length;
            jQuery(amountInputs).each(function(index,element) {
                jQuery(element).val(distributedAmount);
            });
        }

        //setting autobalance;
        if (amountInputs.length === 2) { //needs amount auto balance
            jQuery(amountInputs).each(function(index,element) {
                var oppositeIndex = index === 0 ? 1 : 0;
                var oppositeInput = amountInputs[oppositeIndex];
                var max = parseFloat(orderTotal);

                element.lastValue = jQuery(element).val();
                jQuery(element).on('input',function(){

                    setTimeout(function(){
                        if (jQuery(element).val() !== element.lastValue) {
                            element.lastValue = jQuery(element).val();
                            jQuery(element).change();
                        }
                    }.bind(element),2000);

                }.bind(element));

                jQuery(element).on('change',function(){
                    var elementValue = parseFloat(jQuery(element).val());

                    if (isNaN(elementValue)) {
                        elementValue = 0;
                    }

                    if (elementValue > max) {
                        elementValue = max;
                    }

                    var oppositeValue = max - elementValue;

                    jQuery(oppositeInput).val(oppositeValue.toFixed(2));
                    jQuery(element).val(elementValue.toFixed(2));

                    var elementId = element.id.split('_');
                    elementId.pop();
                    getBrandWithDelay(elementId.join('_'));

                    var oppositeInputId = oppositeInput.id.split('_');
                    oppositeInputId.pop();
                    getBrandWithDelay(oppositeInputId.join('_'));

                }.bind(element));
            });
        }
    });

    //trigger change events on certain inputs
    var paymentMethodForm = jQuery('#payment_form_' + methodCode);
    //on saved creditCards select.
    paymentMethodForm.find('.savedCreditCardSelect').change();
}

function isNewCard(elementId)
{
    var isNew = false;

    try {
        isNew = jQuery('#' + elementId + '_mundicheckout-SavedCreditCard');
        isNew =
            isNew.children("option:selected").val() === 'new' ||
            typeof isNew.children("option:selected").val() === 'undefined';
    }
    catch(e) {
        isNew = true;
    }

    return isNew;
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
        holder_name: clearHolderName(document.getElementById(elementId + '_mundicheckout-holdername')),
        number: clearCardNumber(document.getElementById(elementId + '_mundicheckout-number')),
        exp_month: document.getElementById(elementId + '_mundicheckout-expmonth').value,
        exp_year: document.getElementById(elementId + '_mundicheckout-expyear').value,
        cvv: clearCvv(document.getElementById(elementId + '_mundicheckout-cvv'))
    };

    jQuery("#" + elementId + "_brand_name").val('');

    if (!isNewCard(elementId)) {
        var brandName = jQuery('#' + elementId + '_mundicheckout-SavedCreditCard')
            .find('option:selected').attr('data-brand');
        jQuery("#" + elementId + "_brand_name").val(brandName);
    }
}

var isElementValueBusy = {};
function getBrandWithDelay(elementId) {
    if (typeof isElementValueBusy[elementId] === 'undefined') {
        isElementValueBusy[elementId] = false;
    }

    if (!isElementValueBusy[elementId]) {
        var lastValue = jQuery("#" + elementId + "_value").val();

        setTimeout(function(){
            var currentValue = jQuery("#" + elementId + "_value").val();
            isElementValueBusy[elementId] = false;
            if (currentValue === lastValue) {
                getBrand(elementId);
                return;
            }
            getBrandWithDelay(elementId);
        }.bind(lastValue,elementId,isElementValueBusy),300);

        isElementValueBusy[elementId] = true;
    }
}

function getBrand(elementId) {

    var brandName = jQuery("#" + elementId +"_mundipaggBrandName").val();
    var baseUrl = jQuery("#baseUrl").val();
    var creditCardNumber = jQuery("#" + elementId +"_mundicheckout-number").val();
    var value = jQuery("#" + elementId +"_value").val();
    var argsObj = {
        elementId : elementId,
        installmentsBaseValue: value
    };

    if (!isNewCard(elementId)) {
        brandName = jQuery("#" + elementId + "_mundicheckout-SavedCreditCard")
            .children("option:selected").attr("data-brand");
        getInstallments(baseUrl, brandName, argsObj);
        return;
    }

    if (typeof creditCardNumber !== 'undefined') {
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
                argsObj
            );
        }
        if (creditCardNumber.length < 6) {
            clearBrand(elementId);
        }
    }
}

function fillBrandData(data,argsObj) {
    if (data.brand != "" && data.brand != undefined) {
        showBrandImage(data.brand,argsObj.elementId);
        getInstallments(jQuery("#baseUrl").val(), data.brandName,argsObj);

        jQuery("#"+argsObj.elementId+"_mundicheckout-creditCard-installments").html("");
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

    jQuery("#"+elementId+"_brand_name").val(brandName);

    jQuery("#"+elementId+"_mundipaggBrandName").val(brandName);
    jQuery("#"+elementId+"_mundipaggBrandImage" ).html(html);
}

/**
 * @param baseUrl
 * @param brandName
 * @param argsObj
 * var argsObj = {
 *      elementId: elementId,
 *      installmentsBaseValue: value
 *  };
 */
function getInstallments(baseUrl, brandName, argsObj) {
    var value = '';
    if(typeof argsObj.installmentsBaseValue !== 'undefined'){
        var tmp = parseFloat(argsObj.installmentsBaseValue.replace(',','.'));
        if (isNaN(tmp)) {
            tmp = 0;
        }
        value = '?value=' + tmp;
    }
    apiRequest(
        baseUrl + '/mp-paymentmodule/creditcard/getinstallments/' + brandName + value,
        '',
        switchInstallments,
        "GET",
        false,
        argsObj
    );
}

function switchInstallments(data,argsObj) {
    jQuery('.disabledBrandMessae').hide();

    if (data) {
        var withoutInterest = MundiPagg.Locale.getTranslaction("without interest");
        var html;

        html = fillInstallments(data);

        jQuery("#"+ argsObj.elementId + "_mundicheckout-creditCard-installments").html(html);
    } else {
        if(argsObj !== undefined && argsObj.elementId != undefined) {
            jQuery("#" + argsObj.elementId + '_disabled_brand_message').show();
        }
    }
}

function fillInstallments(data) {
    var html = '';
    var withoutInterest = MundiPagg.Locale.getTranslaction("without interest");
    var interestPercent = MundiPagg.Locale.getTranslaction("% of interest");
    var of = MundiPagg.Locale.getTranslaction("of");

    for (i=0; i< data.length; i++) {
        data[i].interestMessage = ' ' + withoutInterest;

        if (data[i].interest > 0) {
            data[i].interestMessage =
                " " + MundiPagg.Locale.getTranslaction("with") + " " +
                data[i].interest +
                interestPercent;
        }

         html +=
            "<option value='"+data[i].times+"'>" +
            data[i].times +
            "x " + of + " " +
            data[i].amount +
            data[i].interestMessage +
            "</option>";
    }

    return html;
}

function switchNewSaved(value, elementId) {
    if(value == "new") {
        jQuery(".newCreditCard-" + elementId).show();
        jQuery(".savedCreditCard-" + elementId).hide();
    } else {
        jQuery(".newCreditCard-" + elementId).hide();
        jQuery(".savedCreditCard-" + elementId).show();
    }
}

function toggleMultiBuyerForm(elementId)
{
    var isEnabled =
        jQuery('#' + elementId + '_multi_buyer_enabled:checked').length > 0;
    if (isEnabled) {
        enableMultibuyerForm(elementId);
        return;
    }
    disableMultibuyerForm(elementId);

}

function enableMultibuyerForm(elementId)
{
    jQuery('#' + elementId + '_multi_buyer_enabled').attr('checked', true);
    jQuery("#" + elementId + '_multi_buyer_form_div').show();

    //enabling all children input
    jQuery('#' + elementId + '_multi_buyer_form_div').find('[name]')
        .attr('disabled',false);


    //if multibuyer is enabled, save credit card should be disabled.
    jQuery('#' + elementId + '_mundicheckout-save-credit-card')
        .attr('disabled',true);
}

function disableMultibuyerForm(elementId)
{
    jQuery('#' + elementId + '_multi_buyer_enabled').attr('checked', false);
    jQuery("#" + elementId + '_multi_buyer_form_div').hide();

    //disabling all children input
    jQuery('#' + elementId + '_multi_buyer_form_div').find('[name]')
        .attr('disabled',true);

    //enable editing of save credit-card checkbox
    jQuery('#' + elementId + '_mundicheckout-save-credit-card')
        .attr('disabled',false);
}

function toogleSavedCreditCard(elementId) {
    var isEnabled =
        jQuery('#' + elementId + '_mundicheckout-save-credit-card:checked')
            .length > 0;

    //if isEnabled, it should disable multibuyer checkbox
    if(isEnabled) {
        disableMultibuyerForm(elementId);
        jQuery('#' + elementId + '_multi_buyer_enabled').attr('disabled', true);
        return;
    }

    jQuery('#' + elementId + '_multi_buyer_enabled').attr('disabled', false);
}