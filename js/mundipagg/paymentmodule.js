var callbacks = {
    success: function (data) {
        console.log('sucess');
        console.log(data);
        return false;
    },

    failure: function (data) {
        console.log('failure');
        console.log(data);
        return false;
    }
};

Payment.prototype.save = Payment.prototype.save.wrap(function(save) {
    // I'm not proud of this... :(
    var key = document.getElementById('tokenDiv').getAttribute('data-pkkey');
    document.getElementById('placeLixo').setAttribute('data-mundicheckout-app-id',key);

    // start checkout
    MundiCheckout.init(callbacks.success, callbacks.failure);

    
    var elem = document.getElementById('tokenDiv');
    var event = new Event('submit');
    elem.dispatchEvent(event);

    save();
});
