var Avs = Class.create();

Avs.prototype = {
    initialize: function(service_url, pcode, suburb, country){
	this.serviceUrl = service_url;
	this.prepareAutocomplete(pcode, pcode, suburb, country);
	this.prepareAutocomplete(suburb, pcode, suburb, country);
	this.registerCountryListener(country, pcode);
    },
    prepareAutocomplete: function(el, pcode, suburb, country) {
	if($(el)) {
	    new Autocomplete(el, {
		serviceUrl: this.serviceUrl,
		countryEl: country,
		onSelect: function(value, data) {
		    $(suburb).value = data[0].city;
		    $(pcode).value = data[0].postcode;
		    fireEvent($(pcode), 'change');
		},
		onLoadStart: function() {
		    $(el).addClassName('avs-active');
		},
		onLoadComplete: function() {
		    $(el).removeClassName('avs-active');
		},
		onAway: function() { }
	    });
	}
    },
    registerCountryListener: function(countryId, pcodeId) {
	if($(countryId)) {
	    Event.observe(countryId, 'change', function() {
		if($(countryId).value == "GB") {
		    $(pcodeId).addClassName('validate-postcode-gb');
		} else {
		    $(pcodeId).removeClassName('validate-postcode-gb');
		}
	    });
	}
    }
};

if(typeof Validation != 'undefined') {
    Validation.add('validate-postcode-gb', 'Please enter a valid UK postcode.', function(v) {
        return Validation.get('IsEmpty').test(v) || /(^[A-Z]{1,2}[0-9R][0-9A-Z]?[\s]?[0-9][ABD-HJLNP-UW-Z]{2}$)/i.test(v);
    });
}

function fireEvent(element, event) {
    if (document.addEventListener) {
        // dispatch for firefox, IE9+ & others       
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent(event, true, true); // event type,bubbling,cancelable
        return !element.dispatchEvent(evt);
    } else {
        // dispatch for IE < 9
        var evt = document.createEventObject();
        return element.fireEvent('on' + event, evt);
    }
}