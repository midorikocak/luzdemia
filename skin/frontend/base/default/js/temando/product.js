function pcsFocus()
{
    var obj = $('pcs');
    if (obj.value == empty_pcs_value) {
        obj.value = '';
    }

    obj.style.color = '';
}

function pcsBlur()
{
    var color= '';
    var obj = $('pcs');
    if (!obj.value || (obj.value == empty_pcs_value)) {
        color= 'grey';
        obj.value = empty_pcs_value;
    }

    obj.style.color = color;
}

function getShippingRates()
{
    var qty_value = 1;
    if ($('qty') && $('qty').value && parseInt($('qty').value) && (parseInt($('qty').value) > 0)) {
        qty_value = parseInt($('qty').value);
    }
    if (!$('city').value || !$('postcode').value) {
        alert('Please enter your location');
        return false;
    }
    var options = {};
    $$('select[name^=super_attribute]').each(function (control) {
        if (false === options) {
            return;
        }
        if (!control.value) {
            options = false;
            return;
        } else {
            options[control.name.substr(16, control.name.length - 17)] = control.value;
        }
    });
    if (false == options) {
        alert('Please select product options');
        return false;
    }
    var options_str = '';
    for (var i in options) {
        options_str += i + ':' + options[i] + ';';
    }
    options = {};
    $$('select[name^=options]').each(function (control) {
        if (false === options) {
            return;
        }
        if (control.hasClassName('required-entry')) {
            if (!control.value) {
                options = false;
                return;
            } else {
                options[control.name.substr(8, control.name.length - 9)] = control.value;
            }
        }
    });
    $$('input[name^=options]').each(function (control) {
        if (false === options) {
            return;
        }
        if (control.hasClassName('validate-one-required-by-name')) {
            var items = $$('input[name^=options]');
            var value = false;
            for (var k = 0; k < items.length; k++) {
                if ((items[k].name == control.name) && items[k].checked) {
                    value = items[k].value;
                }
            }
            if (!value) {
                options = false;
                return;
            } else {
                options[control.name.substr(8, control.name.length - 9)] = value;
            }
        }
    });
    if (false == options) {
        alert('Please select product options');
        return false;
    }
    var pr_options_str = '';
    for (var i in options) {
        pr_options_str += i + ':' + options[i] + ';';
    }
    
    //bundle products
    options = {};
    $$('select[name^=bundle_option]').each(function (control) {
        if (false === options) {
            return;
        }
        if (control.hasClassName('required-entry')) {
            if (!control.value) {
                options = false;
                return;
            } else {
                options[control.name.substr(14, control.name.length - 15)] = control.value;
            }
        }
    });
    $$('input[name^=bundle_option]').each(function (control) {
        if (false === options) {
            return;
        }
        if (control.hasClassName('validate-one-required-by-name')) {
            var items = $$('input[name^=bundle_option]');
            var value = false;
            for (var k = 0; k < items.length; k++) {
                if ((items[k].name == control.name) && items[k].checked) {
                    value = items[k].value;
                }
            }
            if (!value) {
                options = false;
                return;
            } else {
                options[control.name.substr(13, control.name.length - 14)] = value;
            }
        }
    });
    if (false == options) {
        alert('Please select product options');
        return false;
    }
    var bundle_options_str = '';
    for (var i in options) {
        bundle_options_str += i + ':' + options[i] + ';';
    }
    
    //get rid of '[' & ']' - for check-boxes with syntax xxx[14][]
    var pattern = /(\[|\])/g;
    bundle_options_str = bundle_options_str.replace(pattern, "");

    $('estimate_items').update('Loading...');
    new Ajax.Request(shipping_estimator_url, {
        parameters: {
            country_id: $('country_id').value,
            city      : $('city').value,
            postcode  : $('postcode').value,
            product_id: $('product_id').value,
            qty       : qty_value,
            options   : options_str,
            pr_options: pr_options_str,
	    bl_options: bundle_options_str
        },
        onComplete: function(resp){
            $('estimate_items').update(resp.responseText);
        },
        method: 'post'
    });
}