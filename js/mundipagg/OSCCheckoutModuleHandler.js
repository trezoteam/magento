console.log('OSCCheckoutModuleHandler');

var OSCCheckoutModuleHandler = function (methodCode) {
    AbstractCheckoutModuleHandler.call(this,methodCode);
};
var MundipaggCheckoutHandler = OSCCheckoutModuleHandler;

OSCCheckoutModuleHandler.prototype =
    Object.create(AbstractCheckoutModuleHandler.prototype, {
        'constructor': OSCCheckoutModuleHandler
    });

