var rafDetect = navigator.userAgent.toLowerCase();
var rafOS,rafBrowser,rafVersion,rafTotal,rafThestring;

function rafGetBrowserInfo() {
    if (rafCheckIt('konqueror')) {
        rafBrowser = "Konqueror";
        rafOS = "Linux";
    }
    else if (rafCheckIt('safari')) rafBrowser     = "Safari";
    else if (rafCheckIt('omniweb')) rafBrowser     = "OmniWeb";
    else if (rafCheckIt('opera')) rafBrowser     = "Opera";
    else if (rafCheckIt('webtv')) rafBrowser     = "WebTV";
    else if (rafCheckIt('icab')) rafBrowser     = "iCab";
    else if (rafCheckIt('msie')) rafBrowser     = "Internet Explorer";
    else if (!rafCheckIt('compatible')) {
        rafBrowser = "Netscape Navigator";
        rafVersion = rafDetect.charAt(8);
    }
    else rafBrowser = "An unknown browser";

    if (!rafVersion) rafVersion = rafDetect.charAt(place + rafThestring.length);

    if (!rafOS) {
        if (rafCheckIt('linux')) rafOS         = "Linux";
        else if (rafCheckIt('x11')) rafOS     = "Unix";
        else if (rafCheckIt('mac')) rafOS     = "Mac";
        else if (rafCheckIt('win')) rafOS     = "Windows";
        else rafOS                             = "an unknown operating system";
    }
}

function rafCheckIt(string) {
    place = rafDetect.indexOf(string) + 1;
    rafThestring = string;
    return place;
}

/*-----------------------------------------------------------------------------------------------*/

//Event.observe(window, 'load', rafInitialize, false);
Event.observe(window, 'load', rafGetBrowserInfo, false);
//Event.observe(window, 'unload', Event.unloadCache, false);

var Referafriend = Class.create();
Referafriend.prototype = {
    yPos : 0,
    xPos : 0,
    isLoaded : false,

    initialize: function(ctrl, url) {
        if (url){
            this.content = url;
        } else {
            this.content = ctrl.href;
        }
        ctrl.observe('click', function(event){this.activate();Event.stop(event);}.bind(this));
        $('referafriend').hide().observe('click', (function(event) {if ((event.element().id == 'referafriend-cancel') || (event.element().id == 'span-referafriend-cancel')  ) this.deactivate(); }).bind(this));
        $('referafriend-overlay').observe('click', (function(event) {this.deactivate();}).bind(this));
    },

    activate: function(){
        if (rafBrowser == 'Internet Explorer'){
            this.getScroll();
            this.prepareIE('100%', 'hidden');
            this.setScroll(0,0);
            this.hideSelects('hidden');
        }
        this.displayReferafriend("block");
    },

    prepareIE: function(height, overflow){
        bod = document.getElementsByTagName('body')[0];
        bod.style.height = height;
        bod.style.overflow = overflow;

        htm = document.getElementsByTagName('html')[0];
        htm.style.height = height;
        htm.style.overflow = overflow;
    },

    hideSelects: function(visibility){
        selects = document.getElementsByTagName('select');
        for(i = 0; i < selects.length; i++) {
            selects[i].style.visibility = visibility;
        }
    },

    getScroll: function(){
        if (self.pageYOffset) {
            this.yPos = self.pageYOffset;
        } else if (document.documentElement && document.documentElement.scrollTop){
            this.yPos = document.documentElement.scrollTop;
        } else if (document.body) {
            this.yPos = document.body.scrollTop;
        }
    },

    setScroll: function(x, y){
        window.scrollTo(x, y);
    },

    displayReferafriend: function(display){
        $('referafriend-overlay').style.display = display;
        $('referafriend').style.display = display;
        if (display != 'none') this.loadInfo();
    },

    loadInfo: function() {
        $('referafriend').className = "loading";
        var myAjax = new Ajax.Request(
            this.content,
            {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
        );

    },

    processInfo: function(response){
        var json = JSON.parse(response.responseText);
        $('rafContent').update(json.content);
        $('referafriend').className = "done";
        this.isLoaded = true;
        this.actions();
    },

    actions: function(){
        rafActions = $$('rafAction');
    },

    deactivate: function(){
        //Element.remove($('rafContent'));

        if (rafBrowser == "Internet Explorer"){
            this.setScroll(0,this.yPos);
            this.prepareIE("auto", "auto");
            this.hideSelects("visible");
        }

        this.displayReferafriend("none");
    }
};

/*-----------------------------------------------------------------------------------------------*/


function addReferafriendMarkup() {
    bod                 = document.getElementsByTagName('body')[0];
    overlay             = document.createElement('div');
    overlay.id        = 'referafriend-overlay';
    raf                    = document.createElement('div');
    raf.id                = 'referafriend';
    raf.className     = 'loading';
    raf.innerHTML    = '<div id="rafLoadMessage">' +
                          '<p>Loading</p>' +
                          '</div>';
    bod.appendChild(overlay);
    bod.appendChild(raf);
}

var ReferafriendForm = Class.create();
ReferafriendForm.prototype = {
    initialize: function(form){
        this.form = form;
        if ($(this.form)) {
            this.sendUrl = $(this.form).action;
            $(this.form).observe('submit', function(event){this.send();Event.stop(event);}.bind(this));
        }
        this.loadWaiting = false;
        this.validator = new Validation(this.form);
        this.onSuccess = this.success.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
        this.onFailure = this.resetLoadWaiting.bindAsEventListener(this);
        var container = $('invite-login-container');
        if (container && container.style.display == 'none'){
            this._disableEnableAll(container, true);
        }
    },

    send: function(){
        if (!this.validator.validate()) {
            return false;
        }
        this.setLoadWaiting(true);
        var request = new Ajax.Request(
            this.sendUrl,
            {
                method:'post',
                onComplete: this.onComplete,
                onSuccess: this.onSuccess,
                onFailure: this.onFailure,
                parameters: Form.serialize(this.form)
            }
        );
    },

    success: function(transport) {
        this.resetLoadWaiting(transport);
        if (transport && transport.responseText) {
            var json = JSON.parse(transport.responseText);
            $('rafContent').update(json.content);
        }

        return false;
       /**if (transport && transport.responseText){
            try{
                response = eval('(' + transport.responseText + ')');
            } catch (e) {
                response = {};
            }
        }
        if (response.error){
            if (response.error_type == 'no_login'){
                var container = $('invite-login-container');
                if (container){
                    container.show();
                    this._disableEnableAll(container, false);
                }
            }
            if ((typeof response.message) == 'string') {
                alert(response.message);
            } else {
                alert(response.message.join("\n"));
            }
            return false;
        }**/
    },

    _disableEnableAll: function(element, isDisabled) {
        var descendants = element.descendants();
        for (var k in descendants) {
            descendants[k].disabled = isDisabled;
        }
        element.disabled = isDisabled;
    },

    setLoadWaiting: function(isDisabled) {
        var container = $('invite-button-container');
        if (isDisabled){
            container.setStyle({opacity:.5});
            this._disableEnableAll(container, true);
            Element.show('invite-please-wait');
            this.loadWaiting = true;
        } else {
            container.setStyle({opacity:1});
            this._disableEnableAll(container, false);
            Element.hide('invite-please-wait');
            this.loadWaiting = false;
        }
    },

    resetLoadWaiting: function(transport){
        this.setLoadWaiting(false);
    }
};