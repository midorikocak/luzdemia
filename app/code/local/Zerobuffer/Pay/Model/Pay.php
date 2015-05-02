<?php
class Zerobuffer_Pay_Model_Pay extends Mage_Payment_Model_Method_Cc {
    protected $_code = 'pay';
    protected $_formBlockType = 'pay/form_pay';
    protected $_infoBlockType = 'pay/info_pay';
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canRefund = false;
    protected $_canSaveCc = false;
	protected $_canUseCheckout          = true;
    
    public function process($data) {
        if ($data['cancel'] == 1) {
            $order->getPayment()->setTransactionId(null)->setParentTransactionId(time())->void();
            $message = 'Unable to process Payment';
            $order->registerCancellation($message)->save();
        }
    }
    
    /** For capture **/
    public function capture(Varien_Object $payment, $amount) {
        $order  = $payment->getOrder();

        $result = $this->callApi($payment, $amount, 'Sale');

        $errorMsg = false;
        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg  = $this->_getHelper()->__('Error Processing the request');
        } 
        else {
            if ($result['status'] == 1) {
                $payment->setTransactionId($result['transaction_id']);
                $payment->setIsTransactionClosed(1);

                // Save additional variables as needed
                $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array(
                    'key1' => 'value1',
                    'key2' => 'value2'
                ));
            }
            else {
                Mage::throwException($errorMsg);
            }
        }

        if ($errorMsg) {
            Mage::throwException($errorMsg);
        }

        return $this;
    }
    
    
    /** For authorization **/
    public function authorize(Varien_Object $payment, $amount) {
        $order    = $payment->getOrder();
        $items    = $order->getAllVisibleItems();

        $result   = $this->callApi($payment, $amount, 'Authorization');
        $errorMsg = "";

        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg  = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        } 
        else {            
            if ($result['status'] == 1) {
                $payment->setTransactionId($result['transaction_id']);
                $payment->setIsTransactionClosed(1);
                $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array(
                    'key1' => 'value1',
                    'key2' => 'value2'
                ));
                
                $order->addStatusToHistory($order->getStatus(), 'Payment Sucessfully Placed with Transaction ID' . $result['transaction_id'], false);
                $order->save();
            } 
            else {
                $errorMsg  = $this->_getHelper()->__('Your credit card information appears to be invalid');
                Mage::throwException($errorMsg);
            }
        }
        
        return $this;
    }
    
    public function processBeforeRefund($invoice, $payment)
    {
        return parent::processBeforeRefund($invoice, $payment);
    }

    public function refund(Varien_Object $payment, $amount)
    {
        $order  = $payment->getOrder();
        $result = $this->callApi($payment, $amount, 'refund');
        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg  = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        }

        return $this;    
    }

    public function processCreditmemo($creditmemo, $payment)
    {
        return parent::processCreditmemo($creditmemo, $payment);
    }
    
    /**
     *
     *  @param (type) This string can be used to tell the API
     *               if the call is for Sale or Authorization
     */
    private function callApi(Varien_Object $payment, $amount, $type = "")
    {
        if ($amount > 0) {
			$order            = $payment->getOrder();
		    $ccNumber         = $payment->getCcNumber();
			$expiratonMonth   = $payment->getCcExpMonth();
		    $expirationYear   = $payment->getCcExpYear();
		    $billingAddress   = $order->getBillingAddress();
		    $street           = $billingAddress->getStreet(1);
		    $postcode         = $billingAddress->getPostcode();
		    $cscCode          = $payment->getCcCid();

            $approved = false;

			error_log(print_r($order,true));


			$url = "https://secure.payu.com.tr/order/alu.php";

			$secretKey = 'SECRET_KEY';
			$arParams = array(
				//The Merchant's ID
				"MERCHANT" => "3405094005175",
				//order external reference number in Merchant's system
				"ORDER_REF" => rand(1000,9999),
				"ORDER_DATE" => gmdate('Y-m-d H:i:s'),

				//First product details begin
				"ORDER_PNAME[0]" => "Ticket1",
				"ORDER_PCODE[0]" => "TCK1",
				"ORDER_PINFO[0]" => "Barcelona flight",
				"ORDER_PRICE[0]" => "100",
				"ORDER_QTY[0]" => "1",
				//First product details end

				//Second product details begin
				"ORDER_PNAME[1]" => "Ticket2",
				"ORDER_PCODE[1]" => "TCK2",
				"ORDER_PINFO[1]" => "London flight",
				"ORDER_PRICE[1]" => "200",
				"ORDER_QTY[1]" => "1",
				//Second product details end

				"PRICES_CURRENCY" => "TRY",
				"PAY_METHOD" => "CCVISAMC",//to remove
				"SELECTED_INSTALLMENTS_NUMBER" => "3",
				"CC_NUMBER" => "4355084355084358",
				"EXP_MONTH" => "01",
				"EXP_YEAR" => "2016",
				"CC_CVV" => "123",
				"CC_OWNER" => "FirstName LastName",

				//Return URL on the Merchant webshop side that will be used in case of 3DS enrolled cards authorizations.
				"BACK_REF" => "https://www.example.com/alu/3ds_return.php",
				"CLIENT_IP" => "127.0.0.1",
				"BILL_LNAME" => "John",
				"BILL_FNAME" => "Doe",
				"BILL_EMAIL" => "shopper@payu.ro",
				"BILL_PHONE" => "1234567890",
				"BILL_COUNTRYCODE" => "TR",

				//Delivery information
				"DELIVERY_FNAME" => "John",
				"DELIVERY_LNAME" => "Smith",
				"DELIVERY_PHONE" => "0729581297",
				"DELIVERY_ADDRESS" => "3256 Epiphenomenal Avenue",
				"DELIVERY_ZIPCODE" => "55416",
				"DELIVERY_CITY" => "Minneapolis",
				"DELIVERY_STATE" => "Minnesota",
				"DELIVERY_COUNTRYCODE" => "MN",
			);

//begin HASH calculation
			ksort($arParams);

			$hashString = "";

			foreach ($arParams as $key=>$val) {
				$hashString .= strlen($val) . $val;
			}

			$arParams["ORDER_HASH"] = hash_hmac("md5", $hashString, $secretKey);
//end HASH calculation

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arParams));
			$response = curl_exec($ch);

			$curlerrcode = curl_errno($ch);
			$curlerr = curl_error($ch);

			if (empty($curlerr) && empty($curlerrcode)) {
				$parsedXML = @simplexml_load_string($response);
				if ($parsedXML !== FALSE) {

					//Get PayU Transaction reference.
					//Can be stored in your system DB, linked with your current order, for match order in case of 3DSecure enrolled cards
					//Can be empty in case of invalid parameters errors
					$payuTranReference = $parsedXML->REFNO;

					if ($parsedXML->STATUS == "SUCCESS") {

						//In case of 3DS enrolled cards, PayU will return the extra XML tag URL_3DS that contains a unique url for each
						//transaction. For example https://secure.payu.com.tr/order/alu_return_3ds.php?request_id=2Xrl85eakbSBr3WtcbixYQ%3D%3D.
						//The merchant must redirect the browser to this url to allow user to authenticate.
						//After the authentification process ends the user will be redirected to BACK_REF url
						//with payment result in a HTTP POST request - see 3ds return sample.
						if (($parsedXML->RETURN_CODE == "3DS_ENROLLED") && (!empty($parsedXML->URL_3DS))) {
							header("Location:" . $parsedXML->URL_3DS);
							die();
						}

						echo "SUCCESS [PayU reference number: " . $payuTranReference . "]";
					} else {
						echo "FAILED: " . $parsedXML->RETURN_MESSAGE . " [" . $parsedXML->RETURN_CODE . "]";
						if (!empty($payuTranReference)) {
							//the transaction was register to PayU system, but some error occured during the bank authorization.
							//See $parsedXML->RETURN_MESSAGE and $parsedXML->RETURN_CODE for details
							echo " [PayU reference number: " . $payuTranReference . "]";
						}
					}
				}
			} else {
				//Was an error comunication between servers
				echo "cURL error: " . $curlerr;
			}
		    
            // This is where you would make your cURL call (or whatever)
            // to your payment processing provider, such as Paytrace.

            if ($approved == true) { // 
                return array( 'status' => 1, 'transaction_id' => time(), 'fraud' => 0 );
            } else { 
                return array( 'status' => 0, 'transaction_id' => time(), 'fraud' => 0 );
            }
        } else {
            $error = Mage::helper('pay')->__('Invalid amount for authorization.');
			return array( 'status' => 0, 'transaction_id' => time(), 'fraud' => 0 );
        }
    }
}
?>
