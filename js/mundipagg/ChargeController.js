var initDialog = false;
var currentCharge = {};

var showChargeDialog = function(operation,element) {

    if (!initDialog) {
        initDialog = true;
        var nodes = document.getElementsByName('total_or_partial');
        for (var i = 0, l = nodes.length; i < l; i++)
        {
            nodes[i].onchange = checkTotalOrPartial;
        }
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

var getChargeDataFromElement =  function(element) {
    return {
        id: element.parentElement.parentElement.childElements()[0].innerHTML.trim(),
        stringValue: element.parentElement.parentElement.childElements()[1].innerHTML.trim(),
        centsValue: element.parentElement.parentElement.childElements()[1].innerHTML.trim().replace(/\D/g, ''),
        typeName: element.parentElement.parentElement.childElements()[3].innerHTML.trim()
    };
};

var hideChargeDialog = function() {
    var popup = document.getElementById('charge-dialog');
    var modal = document.getElementById('message-popup-window-mask');
    modal.hide();
    popup.hide();
};

var resetChargeDialog = function (data) {
    currentCharge = data.charge;
    document.getElementById('charge-operation-value').value = '';
    document.getElementById('charge-operation-credential').value = '';

    document.getElementById('charge-id').innerHTML = data.charge.id;
    document.getElementById('charge-stringValue').innerHTML = data.charge.stringValue;
    document.getElementById('charge-typeName').innerHTML = data.charge.typeName;

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
};