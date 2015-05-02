$jsq=jQuery.noConflict();

var EsNewsSubscribers = {

    cookieLiveTime: 100,

    cookieName: 'es_newssubscriber',

    baseUrl: '',

    setCookieLiveTime: function(value)
    {
        this.cookieLiveTime = value;
    },

    setCookieName: function(value)
    {
        this.cookieName = value;
    },

    setBaseUrl: function(url)
    {
        this.baseUrl = url;
    },

    getBaseUrl: function(url)
    {
        return this.baseUrl;
    },
    createCookie: function() {
        var days = this.cookieLiveTime;
        var value = 1;
        var name = this.cookieName;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = escape(name)+"="+escape(value)+expires+"; path=/";
    },

    readCookie: function(name) {
        var name = this.cookieName;
        var nameEQ = escape(name) + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return unescape(c.substring(nameEQ.length,c.length));
        }
        return null;
    },

    boxClose: function()
    {
        $jsq('#esns_background_layer').fadeOut();
    },

    boxOpen: function()
    {
        $jsq('#esns_background_layer').fadeIn();
    }
}

$jsq(function() {
    if (EsNewsSubscribers.readCookie() != 1) {
        EsNewsSubscribers.createCookie();
        EsNewsSubscribers.boxOpen();
    }
    $jsq('#esns_background_layer').css('height', $jsq(document).height()+'px');
    $jsq('#esns_box_layer').css('margin-top', (($jsq(window).height()-$jsq('#esns_box_layer').height()) /2)+'px');
    $jsq('#esns_submit').click(function(){
        var email = $jsq('#esns_email').val();
        $jsq.post(EsNewsSubscribers.getBaseUrl()+'newsletter/subscriber/newajax/', {'email':email}, function(resp) {
            var response = $jsq.parseJSON(resp);
            if (response['errorMsg']) {
                $jsq('#esns_box_subscribe_response_error').html(response['errorMsg']);
            } else {
                $jsq('#esns_box_subscribe_response_error').html('');
                $jsq('#esns_box_subscribe_response_success').html(response['successMsg']);
                $jsq('#esns_box_subscribe_form').css('display','none');
                $jsq('#esns_box_subscribe_response_success').css('display','block');
                setTimeout('EsNewsSubscribers.boxClose()', 5000)
            }
        });
    });
    $jsq('#esns_box_close').click(function(){
        EsNewsSubscribers.boxClose();
    });

});