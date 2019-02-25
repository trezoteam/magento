var FireCheckoutModuleHandler = function (methodCode) {
    AbstractCheckoutModuleHandler.call(this,methodCode);
};
var MundiPaggCheckoutHandler = FireCheckoutModuleHandler;

FireCheckoutModuleHandler.prototype =
    Object.create(AbstractCheckoutModuleHandler.prototype, {
        'constructor': FireCheckoutModuleHandler
    });

FireCheckoutModuleHandler.prototype.getCurrentPaymentMethod = function() {
    return payment.currentMethod;;
};

FireCheckoutModuleHandler.prototype.init = function() {
    return;
    OnestepcheckoutCore.updater.onRequestCompleteFn = function (transport) {
        try {
            var response = JSON.parse(transport.responseText.replace(/\n/g,""));

        } catch(e) {
            //error
            var response = {
                blocks: {}
            };
        }

        var action = this._getActionFromUrl(transport.request.url);
        this.removeActionBlocksFromQueue(action, response);
        this.currentRequest = null;
        if (this.requestQueue.length > 0) {
            this._clearQueue();
            var args = this.requestQueue.shift();
            this.runRequest(args[0], args[1]);
        }

        // payment form reload fix
        OSCPayment.initObservers();

        // for Discount purpose...
        // OSCShipment.switchToMethod();

        if (Object.keys(response).includes('grand_total')) {
            var grandTotal = response.grand_total.replace(/\D/g, '');
            grandTotal = parseFloat(grandTotal/100).toFixed(2);
            jQuery('.mundipaggMultiPaymentSubtotal span').html(response.grand_total);

            jQuery('.mundipagg-grand-total').each(function () {
                if(grandTotal > 0) {
                    jQuery(this).val(grandTotal);
                }
            });

            jQuery('.savedCreditCardSelect').each(function () {
                jQuery(this).change();
            });

            MundiPagg.grandTotal = grandTotal;
            Object.keys(MundiPagg.paymentMethods).each(function(method){
                MundiPagg.paymentMethods[method].setValueInputAutobalanceEvents();
                MundiPagg.paymentMethods[method].updateInputBalanceValues();
            });
        }
    };
};

FireCheckoutModuleHandler.prototype.setSavePaymentInterceptor = function () {
    var _self = this;

    FireCheckout.prototype.save = FireCheckout.prototype.save.wrap(function(save) {

        _self.resetBeforeCheckout(save);

        code = _self.methodCode.split('_');
        methodName = code[1];
        if(!_self.isHandlingNeeded() || !_self.hasCardInfo()) {
            return _self.placeOrderFunction();
        }

        _self.updateInputBalanceValues();

        //for each of creditcard forms
        var type = (_self.methodCode.indexOf("voucher") >= 0) ? 'voucher' : 'creditcard';
        jQuery('.' + _self.methodCode + '_' + type + '_tokenDiv').each(function(index, element) {
            var elementId = element.id.replace('_tokenDiv', '');
            if (isNewCard( elementId) ) {
                var key = document.getElementById(element.id)
                    .getAttribute('data-mundicheckout-app-id');
                var validator = new Validation(payment.form);
                if (payment.validate() && validator.validate()) {
                    getCreditCardToken(key, elementId, function(response){
                        _self.handleTokenGenerationResponse(response,element);
                    }.bind(_self));
                }
                return;
            }
            _self.tokenCheckTable[element.id] = true;
            return _self.placeOrderFunction();
        }.bind(_self));
    }.bind(_self));
};