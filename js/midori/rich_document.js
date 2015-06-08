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
      if(angular.element('#billing-address-select').is(':visible')){
         var billingText = angular.element('#billing-address-select option:selected').text();
      }
      
      if(angular.element('#shipping-address-select').is(':visible')){
         var shippingText = angular.element('#shipping-address-select option:selected').text();
      }
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
      
      // Algorithm
      // Check if visible billing registered address
      // if visible add control to checkbox
      if(jQuery('#billing-address-select').length!=0)
      {
         getShippingBillingTexts();
      }
      // if changed check if shipping visible
      // if shipping visible get shipping values from that input
      // else get shipping values from billing input
      

    	$scope.billing = {};
    	$scope.shipping = {};
          
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
