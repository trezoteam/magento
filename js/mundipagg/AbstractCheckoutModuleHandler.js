var AbstractCheckoutModuleHandler = function (methodCode) {
    if (this.constructor === AbstractCheckoutModuleHandler) {
        throw new Error(
            "Abstract class '" + this.constructor.name + "' can't be instantiated!"
        );
    }

    this.methodCode = methodCode;
    this.resetBeforeCheckout();
};

AbstractCheckoutModuleHandler.prototype.resetBeforeCheckout = function (placeOrderFunction,callerObject) {
    this.tokenCheckTable = {};
    this.placeOrderFunction = placeOrderFunction;
    this.callerObject = callerObject;
};

AbstractCheckoutModuleHandler.prototype.callPlaceOrderFunction = function() {
  if(typeof this.callerObject === 'undefined') {
      return this.placeOrderFunction();
  }
  this.callerObject[this.placeOrderFunction.name]();
};

AbstractCheckoutModuleHandler.prototype.isHandlingNeeded = function () {
    return !(
        this.getCurrentPaymentMethod() !== this.methodCode
    );
};

//@Todo this method do not belongs to this class...
AbstractCheckoutModuleHandler.prototype.hasCardInfo = function () {
    var creditCardTokenDiv = '.'  + this.methodCode + "_creditcard_tokenDiv";
    var hasCardInfo = false;
    var _self = this;

    jQuery(creditCardTokenDiv).each(function(index, element) {
        _self.tokenCheckTable[element.id] = false;
        hasCardInfo = true;
    }.bind(_self));

    return hasCardInfo;
};

//@Todo this method do not belongs to this class...
AbstractCheckoutModuleHandler.prototype.handleTokenGenerationResponse = function(response,element) {
    var _self = this;
    var elementId = element.id.replace('_tokenDiv', '');
    var tokenElement = document.getElementById( elementId + '_mundicheckout-token');
    if (response !== false) {
        tokenElement.value = response.id;

        jQuery("#"+elementId+"_mundipagg-invalid-credit-card").hide();
        jQuery("#"+elementId+"_brand_name").val(response.card.brand);
        this.tokenCheckTable[element.id] = true;

        //check if all tokens are generated.
        var canSave = true;
        jQuery('.' + this.methodCode+ "_creditcard_tokenDiv").each(function(index,_element) {
            if (_self.tokenCheckTable[_element.id] === false) {
                canSave = false;
            }
        }.bind(_self));
        if (canSave) {
            this.callPlaceOrderFunction();
        }
        return;
    }
    tokenElement.value = "";
    jQuery("#"+elementId+"_mundipagg-invalid-credit-card").show();
};

//@Todo this method do not belongs to this class...
AbstractCheckoutModuleHandler.prototype.updateInputBalanceValues = function() {
    //foreach value input of the paymentMethod
    //update input balance values
    jQuery('#payment_form_' + this.methodCode)
        .find('.multipayment-value-input')
        .each(
            function(index,element)
            {
                jQuery(element).change();
            }
        );
};

AbstractCheckoutModuleHandler.prototype.setSavePaymentInterceptor = function() {
    throw new Error("'setSavePaymentInterceptor' is abstract!");
};

AbstractCheckoutModuleHandler.prototype.getCurrentPaymentMethod = function() {
    throw new Error("'getCurrentPaymentMethod' is abstract!");
};

