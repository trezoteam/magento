console.log('OSCCheckoutModuleHandler');

var OSCCheckoutModuleHandler = function (methodCode) {
    AbstractCheckoutModuleHandler.call(this,methodCode);
};
var MundipaggCheckoutHandler = OSCCheckoutModuleHandler;

OSCCheckoutModuleHandler.prototype =
    Object.create(AbstractCheckoutModuleHandler.prototype, {
        'constructor': OSCCheckoutModuleHandler
    });

OSCCheckoutModuleHandler.prototype.getCurrentPaymentMethod = function() {
    return OSCPayment.currentMethod;
};

OSCCheckoutModuleHandler.prototype.setSavePaymentInterceptor = function () {
    var _self = this;
    if (Object.keys(MundiPagg.paymentMethods).length === 1) {
        OSCForm.placeOrderButton.stopObserving('click');
    }
    OSCForm.placeOrderButton.observe('click',function(){
        if (OSCForm.validate()) {
            _self.resetBeforeCheckout(OSCForm.placeOrder,OSCForm);
            if(!_self.isHandlingNeeded()) {
                return;
            }

            if (!_self.hasCardInfo()) {
                return OSCForm.placeOrder();
            }

            _self.updateInputBalanceValues();

            //for each of creditcard forms
            jQuery('.' + _self.methodCode + "_creditcard_tokenDiv").each(function(index,element) {
                var elementId = element.id.replace('_tokenDiv', '');
                if (isNewCard(elementId)) {
                    var key = document.getElementById(element.id)
                        .getAttribute('data-mundicheckout-app-id');
                    getCreditCardToken(key, elementId, function(response){
                        _self.handleTokenGenerationResponse(response,element);
                    }.bind(_self));
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
                return OSCForm.placeOrder();
            }
        }
    }.bind(_self));
};

