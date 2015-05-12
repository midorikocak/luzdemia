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



var conversionOptions = {
   googleAnalyticsId: 'UA-46592405-1',
   fbPixelId: '993172604039692',
   fbRegisterTrackingId: '6023454191700',
   fbPurchaseTrackingId: '6023454158300'
};

function Conversion(options){
   //console.log('DEBUG: constructor called.');
   
   this.fbRegisterTrackingId = options.fbRegisterTrackingId;
   //this.fbLoginTrackingId = options.fbLoginTrackingId;
   this.fbPurchaseTrackingId = options.fbPurchaseTrackingId;
   
   if(typeof window._fbq === "undefined"){
      //console.log('DEBUG: define fb');
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
      document.observe("dom:loaded", function() {
        $$(".footer").first().insert({ after: '<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id='+options.fbPixelId+'&amp;ev=PixelInitialized" /></noscript>' });
      });
   }
   
   if(typeof window.ga === "undefined")
   {
      //console.log('DEBUG: define ga');
      // Google Analytics is Loading
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m);
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
   
   this.userRegister = function(userId,userEmail){
      var values = {'id':userId,'email':userEmail};
      this.prototype.facebookAddData(options.fbRegisterTrackingId,values);
   };
   
   this.userLogin = function(userId,userEmail){
      ga('set', '&uid', userId);
      //var values = {'id':userId,'email':userEmail};
      //this.facebookAddData(options.fbRegisterTrackingId,values);
   };
   
   this.purchaseProduct = function(purchase,products){
      
      for (var key in products) {
        if (p.hasOwnProperty(key)) {
           ga('ec:addProduct', products[key]);
        }
      }
      
      ga('ec:setAction', 'purchase', purchase);
      ga('send', 'pageview');
      this.facebookAddData(options.fbPurchaseTrackingId,purchase);
   };
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
      
      document.observe("dom:loaded", function() {
        $$(".footer").first().insert({ after: '<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev='+trackingId+'&amp;'+this.prototype.fbValues(values)+'&amp;noscript=1" /></noscript>' });
      });
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
   
      productView : function(product){
         if(typeof window.ga !== "undefined"){
            ga('ec:addProduct', product);
            ga('ec:setAction', 'detail');
            ga('send', 'pageview');       // Send product details view with the initial pageview.
         }
      },
   
      productList : function(product){
         if(typeof window.ga !== "undefined"){
            ga('ec:addImpression', product);
         }
      },
   
      addToCart : function(product){
         if(typeof window.ga !== "undefined"){
            ga('ec:addProduct', product);
            ga('ec:setAction', 'add');
            ga('send', 'event', 'Cart', 'Added', product.id);     // Send data using our «Cart» event.
         }
      },
   
      removeFromCart : function(product){
         if(typeof window.ga !== "undefined"){
            if(typeof product !== "undefined"){
               ga('ec:addProduct', product);
               ga('ec:setAction', 'remove');
               ga('send', 'event', 'Cart', 'removed', product.id);  
            }
         }
      
      },
   
      addPromo : function(positionId,promo, place){
         if(typeof window.ga !== "undefined"){
            ga('ec:addPromo', promo);
            ga('ec:setAction', 'promo_click');
            ga('send', 'event', place, positionId, promo.creative);
         }
      },
         
      setCartStep : function(stepNumber){
         if(typeof window.ga !== "undefined"){
            ga('ec:setAction','checkout', {
               'step': stepNumber            // A value of 1 indicates this action is first checkout step.
            });
            ga('send', 'pageview');   // Pageview for cart
         }
      }     
   };

   (function() {
      //console.log('DEBUG: windows loaded');
      Conversions = new Conversion(conversionOptions);
   })();