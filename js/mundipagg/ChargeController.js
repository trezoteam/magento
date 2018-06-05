var dialogIsInited = false;
var currentCharge = {};
var currentOrderId = '';

var confirmChargeOperation = function() {

    currentCharge.credential = document.getElementById('charge-operation-credential').value;
    currentCharge.operationValue =  document.getElementById('charge-operation-value').value;
    currentCharge.operationValue = parseFloat(currentCharge.operationValue) * 100;

    apiRequest('/mp-paymentmodule/charge',currentCharge,function(data){
        if(data !== false) {
            switch(data.status) {
                case 200 :
                    hideChargeDialog();
                    window.reload();
                break;
                default:
                    console.log(data);
            }

        }
    },'POST');
};

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



var showChargeDialog = function(operation,element) {
    if (!dialogIsInited) {
        dialogIsInited = true;
        initDialog();
    }

    var charge = getChargeDataFromElement(element);
    resetChargeDialog({
        operation,
        charge
    });
    var popup = document.getElementById('charge-dialog');
    var modal = document.getElementById('message-popup-window-mask');
    modal.show();
    popup.show();
};

var initDialog = function() {
    var nodes = document.getElementsByName('total_or_partial');
    for (var i = 0, l = nodes.length; i < l; i++)
    {
        nodes[i].onchange = checkTotalOrPartial;
    }

    var operationValue = document.getElementById('charge-operation-value');

    /*operationValue.onchange = function() {
        console.log(this);
        if (this.value > this.max) {
            this.value = this.max;
        }
        if (this.value < this.min) {
            this.value = this.min;
        }
    };*/
};

var hideChargeDialog = function() {
    var popup = document.getElementById('charge-dialog');
    var modal = document.getElementById('message-popup-window-mask');
    modal.hide();
    popup.hide();
};

var getChargeDataFromElement =  function(element) {
    return {
        id: element.parentElement.parentElement.childElements()[0].innerHTML.trim(),
        stringValue: element.parentElement.parentElement.childElements()[1].innerHTML.trim(),
        centsValue: element.parentElement.parentElement.childElements()[1].innerHTML.trim().replace(/\D/g, ''),
        capturedValue: element.parentElement.parentElement.childElements()[2].innerHTML.trim().replace(/\D/g, ''),
        canceledValue: element.parentElement.parentElement.childElements()[3].innerHTML.trim().replace(/\D/g, ''),
        typeName: element.parentElement.parentElement.childElements()[5].innerHTML.trim(),
        orderId: currentOrderId
    };
};

var resetChargeDialog = function (data) {
    currentCharge = data.charge;
    currentCharge.operation = data.operation;

    document.getElementById('charge-operation-value').value = '';
    document.getElementById('charge-operation-credential').value = '';

    document.getElementById('charge-id').innerHTML = data.charge.id;
    document.getElementById('charge-stringValue').innerHTML = data.charge.stringValue;
    document.getElementById('charge-typeName').innerHTML = data.charge.typeName;

    var valueInput = document.getElementById('charge-operation-value');
    valueInput.value = parseInt(data.charge.centsValue) / 100;
    valueInput.max = valueInput.value;

    var elements = document.getElementsByClassName('charge-operation');
    for (var i = 0, l = elements.length; i < l; i++)
    {
        elements[i].innerHTML=data.operation;
    }

    elements = document.getElementsByName('total_or_partial');
    for (var i = 0, l = elements.length; i < l; i++)
    {
        elements[i].checked = false;
    }
    elements[0].checked = true;

    checkTotalOrPartial();

};

var checkTotalOrPartial = function() {
    var elements = document.getElementsByName('total_or_partial');
    var value = '';
    var valueWrapper = document.getElementById('charge-operation-value-wrapper');
    for (var i = 0, l = elements.length; i < l; i++)
    {
        if (elements[i].checked)
        {
            value = elements[i].value;
        }
    }
    valueWrapper.hide();
    if (value == 'partial') {
        valueWrapper.show();
    }
    currentCharge.operationType = value;
};