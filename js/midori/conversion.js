/*!
* Conversion JavaScript Library v0.1
* http://www.mtkocak.com
*
* Things To Do
*
*    1. Define an unique website.
*    2. Define events to be tracked
*    3. Each event has to have an unique identifier
*    4. Define variables. 
*    5. Define common codes to load.
*    6. Add specific elements to the loaded page.
*    7. Write site specific codes. i.e (Magento)
*
* Copyright 2005, 2014 Midori Kocak
* Released under the MIT license
*
* Date: 2015-05-01
*/

/*
* Site Specific Events (Magento)
*
*    1. Site Load
*    2. showProductInList(category,product)
*    3. showProductInView(category,product)
*    4. addToCart(product)
*    5. removeFromCart(product)
*    6. setChecoutActions(actionNumber)
*    7. puchaseProduct(product)
*    8. setPromoCliks(elementInfo)
*    9. User login
*    10. User Register
*    11. addElementToSite(uniqueId)

*/

var options = {
   googleAnalyticsId: 'UA-46592405-1',
   fbPixelId: '993172604039692'
};

function Conversion(options){
   
   this.user = '';
   this.cart = [];
   
   if(typeof window._fbq === "undefined"){
      (function() {
         var _fbq = window._fbq || (window._fbq = []);
         if (!_fbq.loaded) {
            var fbds = document.createElement('script');
            fbds.async = true;
            fbds.src = '//connect.facebook.net/en_US/fbds.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(fbds, s);
            _fbq.loaded = true;
         }
         
         _fbq.push(['addPixelId', options.fbPixelId]);
      })();
      
      window._fbq = window._fbq || [];
      window._fbq.push(['track', 'PixelInitialized', {}]);
      window.onload = function(){
         document.body.innerHTML += '<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id='+options.fbPixelId+'&amp;ev=PixelInitialized" /></noscript>';  
      }
   }
   
   if(typeof window.ga === "undefined")
   {
      // Google Analytics is Loading
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', options.googleAnalyticsId , 'auto');
      ga('require', 'displayfeatures');
      ga('send', 'pageview');
      ga('require', 'ec');
   }else{
      ga('create', options.googleAnalyticsId , 'auto');
      ga('require', 'displayfeatures');
      ga('send', 'pageview');
      ga('require', 'ec');
   }
}
   
   
Conversion.prototype = {
   
   contructor : Conversion,
   
   fbValues: function(obj) {
      var str = [];
      for(var p in obj)
      if (obj.hasOwnProperty(p)) {
         str.push("cd["+ encodeURIComponent(p) + "]" + "=" + encodeURIComponent(obj[p]));
      }
      return str.join("&amp;");
   },
   
   facebookAddData: function(trackingId,values){
      window._fbq.push(['track', trackingId, values]);
      
      window.onload = function(){
         document.body.innerHTML += '<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev='+trackingId+'&amp;'+this.fbValues(values)+'&amp;noscript=1" /></noscript>';  
      }
         },
      
   createPromo : function(promoId, promoName, promoCreative,promoPosition){
      var Promo = {
         'id':promoId,
         'name':promoName,
         'creative': promoCreative,
         'position': promoPosition
      };
      
      return Promo;
   },
   
   userRegister : function(userId,userEmail,trackingId){
      
      var values = {'id':userId,'email':userEmail};
      this.facebookAddData(trackingId,values);
   },
   
   userLogin : function(userId){
      ga('set', '&uid', userId);
   },
   
   purchaseProduct: function(){
      
   },
   
   addToCart : function(product){
      ga('ec:addProduct', product);
      ga('ec:setAction', 'add');
      ga('send', 'event', 'Cart', 'Added', product.id);     // Send data using our «Cart» event.
   },
   
   removeFromCart : function(product){
      ga('ec:addProduct', product);
      ga('ec:setAction', 'remove');
      ga('send', 'event', 'Cart', 'removed', product.id);  
      
   },
   
   addPromo : function(positionId,promo, place){
      ga('ec:addPromo', promo);
      ga('ec:setAction', 'promo_click');
      ga('send', 'event', place, positionId, promo.creative);
   },
         
   setCartStep : function(stepNumber){
      ga('ec:setAction','checkout', {
         'step': stepNumber            // A value of 1 indicates this action is first checkout step.
      });
      ga('send', 'pageview');   // Pageview for cart
   }     
};

Conversions = new Conversion(options);