/**
 * 
 * Algorithm - Midori Kocak
 * 
 * 	1. There are billing and shipping form field and expressions.
 * 	2. If shipping is hidden, and no value, shipping = billing.
 * 	3. Extract order data from cart. Each product data is repeated. (Dream Code)
 * 	4. Append it to expression.
 * 	5. Finalize Rich Document
 * 	6. Add the rich document as an hidden input
 * 	7. Attach the document as html file to email.
 * 
 */
	'use strict';

   function getShippingBillingTexts(){
      var billingText = "";
      var shippingText = "";

      if(angular.element('#billing-address-select').is(':visible')==true && angular.element('#shipping-address-select').is(':visible')==false){
         billingText = angular.element('#billing-address-select option:selected').text();
         shippingText = "";
         
         var billingarray = billingText.split(',');
         
         var billingfirstname = billingarray[0];
         var billingstreet = "";
         for(var i=1; i<billingarray.length;i++)
         {
            billingstreet+=billingarray[i];
         }
         
         var billing = {
            street1:billingstreet, firstname: billingfirstname
         };
         
      }else{
         billingText = angular.element('#billing-address-select option:selected').text();
         
         var billingarray = billingText.split(',');
         
         var billingfirstname = billingarray[0];
         var billingstreet = "";
         for(var i=1; i<billingarray.length;i++)
         {
            billingstreet+=billingarray[i];
         }
         
         var billing = {
            street1:billingstreet, firstname: billingfirstname
         };
         
         shippingText = angular.element('#shipping-address-select option:selected').text();
         
         var shippingarray = shippingText.split(',');
         
         var shippingfirstname = shippingarray[0];
         var shippingstreet = "";
         for(var i=1; i<shippingarray.length;i++)
         {
            shippingstreet+=shippingarray[i];
         }
         
         var shipping = {
            street1:shippingstreet, firstname: shippingfirstname
         };
         
      }
      var result = {shipping:shipping,billing:billing};
      return result;
   }
   
    var app = angular.module('richDocument',['ngSanitize']);

    app.controller('RichDocumentController', ['$scope', function($scope) {
    	
      $scope.currentorder = angular.element('#checkout-review-table-wrapper').html();
      var agreementDate = new Date();
      $scope.currentdate = agreementDate.toLocaleDateString("tr");
      
      /**
      * 1. If logged address selector, then explode
      * 2. if exixst but changed to none, go to else
      * 3. Else: normal operation
      * "name,b,c".split(',');
      *  billing shipping check
      */
      
      var registeredAddress = "";
      // Algorithm
      // Check if visible billing registered address
      // if visible add control to checkbox
      if(jQuery('#billing-address-select').length!=0)
      {
         console.log('kayıtlı adres mevcut');
         registeredAddress = getShippingBillingTexts();
      }
      // if changed check if shipping visible
      
      angular.element('#billing\\:use_for_shipping_yes').change(function(){
         registeredAddress = getShippingBillingTexts();
      });
      
      angular.element('#shipping-address-select').change(function(){
         if(angular.element('#shipping-address-select option:selected').val()==""){
                        registeredAddress = "";
              	$scope.shipping = {};
         }else
         {
         registeredAddress = getShippingBillingTexts();
         }
      });

      angular.element('#billing-address-select').change(function(){
         if(angular.element('#billing-address-select option:selected').val()==""){
            registeredAddress = "";
          $scope.billing = {};
         }else
         {
         registeredAddress = getShippingBillingTexts();
         }
      });
      // if shipping visible get shipping values from that input
      // else get shipping values from billing input
      
      if(registeredAddress == ""){
       	$scope.billing = {};
       	$scope.shipping = {};
      }else{
         if(typeof registeredAddress.shipping != 'undefined'){
            $scope.shipping = registeredAddress.shipping;
         }
         if(typeof registeredAddress.billing != 'undefined'){
            $scope.billing = registeredAddress.billing;
         }
      }
          
        $scope.$watch(function() {return angular.element('#opc-address-form-shipping').hasClass('hidden'); }, function(){
        	if(angular.element('#opc-address-form-shipping').hasClass('hidden') == true)
        		{
        		$scope.shipping = $scope.billing;
        		}else
        			{
        			$scope.shipping = {};
        			}
        });
    }]);
