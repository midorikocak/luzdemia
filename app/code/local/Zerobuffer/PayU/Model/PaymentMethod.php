<?php

/**
 * Our test CC module adapter
 */
class Zerobuffer_PayU_Model_PaymentMethod extends Mage_Payment_Model_Method_Cc {

    /**
     * unique internal payment method identifier
     *
     * @var string [a-z0-9_]
     */
    protected $_code = 'payu';

    /**
     * Here are examples of flags that will determine functionality availability
     * of this module to be used by frontend and backend.
     *
     * @see all flags and their defaults in Mage_Payment_Model_Method_Abstract
     *
     * It is possible to have a custom dynamic logic by overloading
     * public function can* for each flag respectively
     */

    /**
     * Is this payment method a gateway (online auth/charge) ?
     */
    protected $_isGateway = true;

    /**
     * Can authorize online?
     */
    protected $_canAuthorize = true;

    /**
     * Can capture funds online?
     */
    protected $_canCapture = true;

    /**
     * Can capture partial amounts online?
     */
    protected $_canCapturePartial = false;

    /**
     * Can refund online?
     */
    protected $_canRefund = false;

    /**
     * Can void transactions online?
     */
    protected $_canVoid = true;

    /**
     * Can use this payment method in administration panel?
     */
    protected $_canUseInternal = true;

    /**
     * Can show this payment method as an option on checkout payment page?
     */
    protected $_canUseCheckout = true;

    /**
     * Is this payment method suitable for multi-shipping checkout?
     */
    protected $_canUseForMultishipping = true;

    /**
     * Can save credit card information for future processing?
     */
    protected $_canSaveCc = false;

    /**
     * Here you will need to implement authorize, capture and void public methods
     *
     * @see examples of transaction specific public methods such as
     * authorize, capture and void in Mage_Paygate_Model_Authorizenet
     */

    /** For capture * */
    public function capture(Varien_Object $payment, $amount) {
        $order = $payment->getOrder();

        $result = $this->callApi($payment, $amount, 'Sale');

        $errorMsg = false;
        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg = $this->_getHelper()->__('Error Processing the request');
        } else {
            if ($result['status'] == 1) {
                $payment->setTransactionId($result['transaction_id']);
                $payment->setIsTransactionClosed(1);

                // Save additional variables as needed
                $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array(
                    'key1' => 'value1',
                    'key2' => 'value2'
                ));
            } else {
                Mage::throwException($errorMsg);
            }
        }

        if ($errorMsg) {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    /** For authorization * */
    public function authorize(Varien_Object $payment, $amount) {
        $order = $payment->getOrder();
        $items = $order->getAllVisibleItems();


        $payment_request = Mage::app()->getRequest()->getParam('payment');
        $installment = $payment_request[cc_installment];

//        $order            = $payment->getOrder();
//         $shippingAddress   = $order->getShippingAddress();
//         
//         $errorMsg  = $this->_getHelper()->__(Mage::helper('core/http')->getRemoteAddr(). " EEE");
//				Mage::throwException($errorMsg);
//         
//                return $this;


        $result = $this->callApi($payment, $amount, 'Authorization', $installment);
        $errorMsg = "";

        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        } else {
            if ($result['status'] == 1) {
                $payment->setTransactionId($result['transaction_id']);
                $payment->setIsTransactionClosed(1);
                $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array(
                    'key1' => 'value1',
                    'key2' => 'value2'
                ));

                $order->addStatusToHistory($order->getStatus(), 'Payment Sucessfully Placed with Transaction ID' . $result['transaction_id'], false);
                $order->save();
            } else {
                $errorMsg = $this->_getHelper()->__('Your credit card information appears to be invalid');
//				$errorMsg  = print_r($result, true);
                Mage::throwException($errorMsg);
            }
        }

        return $this;
    }

    public function processBeforeRefund($invoice, $payment) {
        return parent::processBeforeRefund($invoice, $payment);
    }

    public function refund(Varien_Object $payment, $amount) {
        $order = $payment->getOrder();
        $result = $this->callApi($payment, $amount, 'refund');
        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    public function processCreditmemo($creditmemo, $payment) {
        return parent::processCreditmemo($creditmemo, $payment);
    }

    /**
     *
     *  @param (type) This string can be used to tell the API
     *               if the call is for Sale or Authorization
     */
    private function callApi(Varien_Object $payment, $amount, $type = "", $installment = 0) {
        if ($amount > 0) {
            $order = $payment->getOrder();
            $ccNumber = $payment->getCcNumber();
            $expiratonMonth = $payment->getCcExpMonth();
            $expirationYear = $payment->getCcExpYear();
            $billingAddress = $order->getBillingAddress();

            $street = $billingAddress->getStreet(1);
            $postcode = $billingAddress->getPostcode();
            $cscCode = $payment->getCcCid();

            $shippingAddress = $order->getShippingAddress();

            $ordered_items = $order->getAllItems();

            $approved = false;

            $url = "https://secure.payu.com.tr/order/alu.php";

            $secretKey = 'T56|)h4B|6)W6n5)z10|';
            $arParams = array(
                //The Merchant's ID
                "MERCHANT" => "LUZDEMIA",
                //order external reference number in Merchant's system
                "ORDER_REF" => rand(1000, 9999),
                "ORDER_DATE" => gmdate('Y-m-d H:i:s'),
                "PRICES_CURRENCY" => "TRY",
                "PAY_METHOD" => "CCVISAMC", //to remove
                "CC_NUMBER" => $payment->getCcNumber(),
                "EXP_MONTH" => $payment->getCcExpMonth(),
                "EXP_YEAR" => $payment->getCcExpYear(),
                "CC_CVV" => $payment->getCcCid(),
                "CC_OWNER" => $payment->getCcOwner(),
                //Return URL on the Merchant webshop side that will be used in case of 3DS enrolled cards authorizations.
                "BACK_REF" => "https://www.luzdemia.com/alu/3ds_return.php",
                "CLIENT_IP" => Mage::helper('core/http')->getRemoteAddr(),
                "BILL_LNAME" => $billingAddress->getLastname(),
                "BILL_FNAME" => $billingAddress->getFirstname(),
                "BILL_EMAIL" => $order->getCustomerEmail(),
                "BILL_PHONE" => $billingAddress->getTelephone(),
                "BILL_COUNTRYCODE" => "TR",
                "BILL_ADDRESS" => $billingAddress->getStreetFull(),
                "BILL_CITY" => $billingAddress->getStreet(1),
                //Delivery information
                "DELIVERY_FNAME" => $shippingAddress->getFirstname(),
                "DELIVERY_LNAME" => $shippingAddress->getLastname(),
                "DELIVERY_PHONE" => $shippingAddress->getTelephone(),
                "DELIVERY_ADDRESS" => $shippingAddress->getStreetFull(),
                "DELIVERY_ZIPCODE" => $shippingAddress->getPostcode(),
                "DELIVERY_CITY" => $shippingAddress->getStreet(1),
                "DELIVERY_STATE" => $shippingAddress->getStreet(1),
                "DELIVERY_COUNTRYCODE" => "TR",
            );

            if ($installment > 0):
                $arParams["SELECTED_INSTALLMENTS_NUMBER"] = $installment;
            endif;
            $productName = "";
            $order_f = 0;
            foreach ($ordered_items as $item) {
                $productName .= $item->getName();
                $order_f++;
            }

            $arParams['ORDER_PNAME[0]'] = "Luzdemia";
            $arParams['ORDER_PCODE[0]'] = "Luz";
            $arParams['ORDER_PINFO[0]'] = $productName;
            $arParams['ORDER_PRICE[0]'] = $amount;
            $arParams['ORDER_QTY[0]'] = "1";
            error_log(print_R($arParams, true));
//return array( 'status' => 0, 'transaction_id' => time(), 'fraud' => 0 );
//begin HASH calculation
            ksort($arParams);

            $hashString = "";

            foreach ($arParams as $key => $val) {
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

                    error_log(print_r($parsedXML, true));

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

                        $approved = true;
                        //echo "SUCCESS [PayU reference number: " . $payuTranReference . "]";
                    } else {
                        //echo "FAILED: " . $parsedXML->RETURN_MESSAGE . " [" . $parsedXML->RETURN_CODE . "]";
                        if (!empty($payuTranReference)) {
                            //the transaction was register to PayU system, but some error occured during the bank authorization.
                            //See $parsedXML->RETURN_MESSAGE and $parsedXML->RETURN_CODE for details
                            //echo " [PayU reference number: " . $payuTranReference . "]";
                        }
                    }
                }
            } else {
                //Was an error comunication between servers
                //echo "cURL error: " . $curlerr;
            }

            if ($approved == true) { //
                return array('status' => 1, 'transaction_id' => time(), 'fraud' => 0);
            } else {
                return array('status' => 0, 'transaction_id' => time(), 'fraud' => 0, 'request' => print_r($parsedXML, true));
            }
        } else {
            $error = Mage::helper('pay')->__('Invalid amount for authorization.');
            return array('status' => 0, 'transaction_id' => time(), 'fraud' => 0);
        }
    }

    function installment_rate($installment = 1, $amount) {

        switch ($installment) {
            case 2: $rate = 0.0449;
                break;
            case 3: $rate = 0.0526;
                break;
            case 4: $rate = 0.0620;
                break;
            case 5: $rate = 0.0661;
                break;
            case 6: $rate = 0.0708;
                break;
            case 7: $rate = 0.0787;
                break;
            case 8: $rate = 0.0811;
                break;
            case 9: $rate = 0.0881;
                break;

            default: $rate = 0;
                break;
        }
        return $rate * $amount;
    }

    function installment_calculator($installment = 0, $amount) {

        switch ($installment) {
            case 2: $rate = 1.0449;
                break;
            case 3: $rate = 1.0526;
                break;
            case 4: $rate = 1.0620;
                break;
            case 5: $rate = 1.0661;
                break;
            case 6: $rate = 1.0708;
                break;
            case 7: $rate = 1.0787;
                break;
            case 8: $rate = 1.0811;
                break;
            case 9: $rate = 1.0881;
                break;

            default: $rate = 0;
                break;
        }
        return $rate * $amount;
    }

}
?>
