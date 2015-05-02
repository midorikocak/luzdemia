function option_update(sole)
{
    if (sole) return;
    
    // build class
    classes = '';
    
    checkboxes = $$('#temando_checkboxes input[type=checkbox]');
    checkboxes.each(function (checkbox) {
        if (checkbox.id.indexOf('temando_checkbox_') === 0) {
            classes += '.' + checkbox.id.replace(/temando_checkbox_/, '') + '_' + (checkbox.checked  ? 'Y' : 'N');
        }
    });
    
    // hide all
    $$('input[name^=shipping_method]').each(function (control) {
	if(control.id.match(/^s_method_temando_(\d+)/) && !control.id.match(/^s_method_temando_(free|flat)/)) {
	    control.up().hide();
	    control.checked = false;
	}
    });
    
    // show those matching the classes
    $$(classes).each(function (control) {
        control.up().show();
    });
}

function option_update_onestep()
{
    // build class
    classes = '';
    
    checkboxes = $$('#temando_checkboxes input[type=checkbox]');
    checkboxes.each(function (checkbox) {
        if (checkbox.id.indexOf('temando_checkbox_') === 0) {
            classes += '.' + checkbox.id.replace(/temando_checkbox_/, '') + '_' + (checkbox.checked  ? 'Y' : 'N');
        }
    });
    
    // hide all
    $$('input[name^=shipping_method]').each(function (control) {
	if(control.id.match(/^s_method_temando_(\d+)/) && !control.id.match(/^s_method_temando_(free|flat)/)) {
	    control.up().hide();
	    control.checked = false;
	}
    });
    
    // show those matching the classes
    $$(classes).each(function (control) {
        control.up().show();
    });
    
    //preselect shipping method if single
    var visible = $$('input[name^=shipping_method]').findAll(function(el) { return el.offsetWidth > 0 && el.offsetHeight > 0; });
	if(visible.size() == 1) {
	    var el = visible.first();
	    $$('input[name^=shipping_method]').each(function (control) {control.checked = false;});
	    el.checked = true;
	    fireEvent(el, 'click');
	}
    }

function includingShipping(getShippingCode) {
    if ((typeof(shippingMe) !== 'undefined') && (shippingMe != null) && shippingMe && shippingMe.length) {
        var newPrice = shippingMe[getShippingCode];
        if (!lastPrice) {
            lastPrice = newPrice;
            if (window.quoteBaseGrandTotal != undefined) {
                quoteBaseGrandTotal += newPrice;
            }
        }
        if (newPrice != lastPrice) {
            if (window.quoteBaseGrandTotal != undefined) {
                quoteBaseGrandTotal += (newPrice-lastPrice);
            }
            lastPrice = newPrice;

        }
    }
    if (window.quoteBaseGrandTotal != undefined && window.checkQuoteBaseGrandTotal != undefined) {
        checkQuoteBaseGrandTotal = quoteBaseGrandTotal;
    }
    return false;
}

function refreshQuotes() {
    //get checked options
    var delivery_options = $$('input[id^=delivery_option_]');
    var params = Form.serializeElements(delivery_options);

    //refresh quotes
    if (checkout.loadWaiting!=false) return;
    var validator = new Validation(shipping.form);
    if (validator.validate()) {
	checkout.setLoadWaiting('shipping-method');
	delivery_options.each(function(control) {
	   control.disabled = true; 
	});
	params += params.length ? '&' + Form.serialize(shipping.form) : Form.serialize(shipping.form);
	var request = new Ajax.Request(
	    shipping.saveUrl,
	    {
		method:'post',
		onComplete: shipping.onComplete,
		onSuccess: shipping.onSave,
		onFailure: checkout.ajaxFailure.bind(checkout),
		parameters: params
	    }
	);
	
    }
}

function refreshQuotesOnestep(url, set_methods_url)
{
    return function()    {
        var form = $('onestepcheckout-form');
        var items = exclude_unchecked_checkboxes($$('input[name^=billing]').concat($$('select[name^=billing]').concat($$('input[id^=delivery_option_]'))));
	//var delivery_options = $$('input[id^=delivery_option_]');
        var names = items.pluck('name');
        var values = items.pluck('value');
        var parameters = {
                shipping_method: $RF(form, 'shipping_method'),
		delivery_option_click: true
        };

        var street_count = 0;
        for(var x=0; x < names.length; x++)    {
            if(names[x] != 'payment[method]')    {

                var current_name = names[x];

                if(names[x] == 'billing[street][]')    {
                    current_name = 'billing[street][' + street_count + ']';
                    street_count = street_count + 1;
                }

                parameters[current_name] = values[x];
            }
        }

        var use_for_shipping = $('billing:use_for_shipping_yes');
        if(use_for_shipping && use_for_shipping.getValue() != '1')    {
            var items = $$('input[name^=shipping]').concat($$('select[name^=shipping]'));
            var shipping_names = items.pluck('name');
            var shipping_values = items.pluck('value');
            var shipping_parameters = {};
            var street_count = 0;

            for(var x=0; x < shipping_names.length; x++)    {
                if(shipping_names[x] != 'shipping_method')    {
                    var current_name = shipping_names[x];
                    if(shipping_names[x] == 'shipping[street][]')    {
                        current_name = 'shipping[street][' + street_count + ']';
                        street_count = street_count + 1;
                    }

                    parameters[current_name] = shipping_values[x];
                }
            }
        }

        var shipment_methods = $$('div.onestepcheckout-shipping-method-block')[0];
        var shipment_methods_found = false;

        if(typeof shipment_methods != 'undefined') {
            shipment_methods_found = true;
        }

        if(shipment_methods_found)  {
            shipment_methods.update('<div class="loading-ajax">&nbsp;</div>');
        }

        var payment_method = $RF(form, 'payment[method]');
        parameters['payment_method'] = payment_method;
        parameters['payment[method]'] = payment_method;

        var payment_methods = $$('div.payment-methods')[0];
        payment_methods.update('<div class="loading-ajax">&nbsp;</div>');

        var totals = get_totals_element();
        totals.update('<div class="loading-ajax">&nbsp;</div>');


        new Ajax.Request(url, {
            method: 'post',
            onSuccess: function(transport)    {
            if(transport.status == 200)    {

                var data = transport.responseText.evalJSON();

                // Update shipment methods
                if(shipment_methods_found)  {
                    shipment_methods.update(data.shipping_method);
                }
                payment_methods.replace(data.payment_method);
                totals.update(data.summary);

                // Add new event handlers

                if(shipment_methods_found)  {
                    $$('dl.shipment-methods input').invoke('observe', 'click', get_separate_save_methods_function(set_methods_url, true));
                }

                $$('div.payment-methods input[name^=payment\[method\]]').invoke('observe', 'click', get_separate_save_methods_function(set_methods_url));

                $$('div.payment-methods input[name^=payment\[method\]]').invoke('observe', 'click', function() {
                    $$('div.onestepcheckout-payment-method-error').each(function(item) {
                        new Effect.Fade(item);
                    });
                });

                if(shipment_methods_found)  {
                    $$('dl.shipment-methods input').invoke('observe', 'click', function() {
                        $$('div.onestepcheckout-shipment-method-error').each(function(item) {
                            new Effect.Fade(item);
                        });
                    });
                }

                if($RF(form, 'payment[method]') != null)    {
                    try    {
                        var payment_method = $RF(form, 'payment[method]');
                        $('container_payment_method_' + payment_method).show();
                        $('payment_form_' + payment_method).show();
                    } catch(err)    {

                    }
                }


            }
        },
        parameters: parameters
        });

    }
}