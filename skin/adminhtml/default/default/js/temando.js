
function clientTypeChangeListener() {
    id_prefix = "fieldset_client_type_";
    var classn = "fieldset-client-type";
    
    $$("." + classn).each(function (fieldset) {
        fieldset.hide();
        fieldset.previous(".entry-edit-head").hide();
    });
    
    if ($(id_prefix + $("client_type").value.toLowerCase())) {
        $(id_prefix + $("client_type").value.toLowerCase()).show();
        $(id_prefix + $("client_type").value.toLowerCase()).previous(".entry-edit-head").show();
    }
    
    if ($("client_type").value.toLowerCase() === 'company') {
        $('company_no').addClassName('required-entry').addClassName('validate-max-30');
    } else {
        $('company_no').removeClassName('required-entry').removeClassName('validate-max-30');
    }
}

Event.observe(window, "load", function () {
    clientTypeChangeListener();
    
    $("client_type").observe("change", function () {
        clientTypeChangeListener();
    });
});


Validation.addAllThese([
    ['validate-max-500', 'Please use less than 500 characters.', {
        maxLength: 500
    }],
    ['validate-max-100', 'Please use less than 100 characters.', {
        maxLength: 100
    }],
    ['validate-max-50', 'Please use less than 50 characters.', {
        maxLength: 50
    }],
    ['validate-max-30', 'Please use less than 30 characters.', {
        maxLength: 30
    }],
    ['validate-max-20', 'Please use less than 20 characters.', {
        maxLength: 20
    }],
    ['validate-max-10', 'Please use less than 10 characters.', {
        maxLength: 10
    }],
    ['validate-min-6', 'Please use at least 6 characters.', {
        minLength: 6
    }]
]);
