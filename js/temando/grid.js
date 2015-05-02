document.observe("dom:loaded", function() {
    $$('form[id*=_massaction-form]').invoke('hide');
    checkboxes = $$('input[name^=massaction]');
    checkboxes.each(function(checkbox) {
        checkbox.setAttribute('disabled', 'disabled');
        checkbox.setAttribute('title', 'Batch booking available in Business Extension');
    });
});