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
    var app = angular.module('richDocument',[]);

    app.controller('RichDocumentController', ['$scope', function($scope) {
    	
      $scope.currentorder = angular.element('#checkout-review-table');
      $scope.currentdate = Date();
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
