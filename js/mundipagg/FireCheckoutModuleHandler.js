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

    var targetNode = jQuery('#checkout-review-load')[0];

    var config = { attributes: true, childList: true, subtree: true };

    var callback = function(mutationsList, observer) {
        var grandTotalHtml = jQuery('#checkout-review-table tr.last .price').html();
        if (grandTotalHtml == undefined) {
            return;
        }

        var grandTotal = grandTotalHtml.replace(/\D/g, '');
        var grandTotal = parseFloat( grandTotal / 100).toFixed(2);

        jQuery('.mundipaggMultiPaymentSubtotal span').html(grandTotalHtml);

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
    };

    var observer = new MutationObserver(callback);

    observer.observe(targetNode, config);
};

FireCheckoutModuleHandler.prototype.setSavePaymentInterceptor = function () {
    var _self = this;

    FireCheckout.prototype.save = FireCheckout.prototype.save.wrap(function(save) {

        _self.resetBeforeCheckout(save);

        if(!_self.isHandlingNeeded()) {
            return _self.placeOrderFunction();
        }

        if(!_self.hasCardInfo()) {
            return _self.placeOrderFunction();
        }

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
            return;
        }.bind(_self));

        var canSend = true;
        Object.keys(_self.tokenCheckTable).each(function(key){
            if (_self.tokenCheckTable[key] === false) {
                canSend = false;
            }
        });
        if (canSend) {
            return _self.placeOrderFunction();
        }
    }.bind(_self));
};