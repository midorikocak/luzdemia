<?php

class Lema21_CustomExport_Service_GenerateCSV {

    private $_orderIds;
    private $_collectionOrders;
    private $_contentCSV;

    public function __construct($ordersId) {
        $this->_orderIds = $ordersId;
    }

    private function _loadOrderObjects() {
        $this->_collectionOrders = array();

        foreach ($this->_orderIds as $id) {
            $instance = Mage::getModel("sales/order")->load($id);
            array_push($this->_collectionOrders, $instance);
        }
    }

    private function _prepareData($templateLine) {
        $this->_contentCSV = "";
        foreach ($templateLine as $t) {
            $headLine.="{$t}\t";
        }
        $this->_contentCSV .=$headLine . "\n";

        //iterate on the orders selected
        foreach ($this->_collectionOrders as $order) {

            $lineItem = "";

            // iterate on the itens in template
            foreach ($templateLine as $t) {

                $invoiceIncrementId = $order->getData("increment_id");
                // order.increment_id => $order->getData("increment_id");
                // getAttributeByCode($attribute, $order)
                $item = "";
                list($object, $attribute) = explode(".", $t);

                switch ($object) {

                    case "billing":
                        $address = $order->getBillingAddress();
                        if ($attribute == "address") {
                            $tmp = "";
                            $tmp .= $address->getStreet("1") . " ";
                            $tmp .= $address->getStreet("2") . " ";
                            $tmp .= $address->getStreet("3") . " ";
                            $tmp .= $address->getStreet("4") . " ";
                            $tmp .= $address->getData("city") . " ";
                            $tmp .= $address->getData("region") . " ";
                            $tmp .= $address->getData("postcode") . " ";
                            $tmp .= $address->getData("telephone") . " ";
                            $item = $tmp;
                        } else {
                            if ($attribute == "name") {
                                $item = $address->getData("firstname") . " " .
                                        $address->getData("lastname");
                            } elseif (strpos($attribute, "street_") !== false) {
                                $street = explode("_", $attribute);
                                $item = $address->getStreet($street[1]);
                            } else {
                                $item = $address->getData($attribute);
                            }
                        }
                        break;

                    case "invoice":
                        $invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceIncrementId);
                        if ($attribute == "created_at") {
                            $item = $invoice->getCreatedAt();
                        }
                        if ($attribute == "updated_at") {
                            $item = $invoice->getUpdatedAt();
                        }
                        break;

                    case "order":
                        $item = $order->getData($attribute);
                        if ($attribute == "grand_total") {
                            $item = str_replace(".", ",", round($item, 2));
                        } if (strpos($attribute, "amount") !== false || strpos($attribute, "refunded") !== false) {
                            $item = str_replace(".", ",", round($item, 2));
                        } elseif ($attribute == "payment_method") {
                            $item = $order->getPayment()->getMethodInstance()->getCode();
                        }
                        break;

                    case "customer":
                        if ($attribute == "name") {
                            $item = $order->getData("customer_firstname") . " " .
                                    $order->getData("customer_lastname");
                        } else {
                            $item = $order->getData("customer_{$attribute}");
                        }
                        break;

                    case "shipping":

                        $address = $order->getShippingAddress();
                        if ($attribute == "tracking_code") {
                            $shipment_collection = Mage::getResourceModel('sales/order_shipment_collection')
                                    ->setOrderFilter($order)
                                    ->load();
                            $tmp = "";
                            foreach ($shipment_collection as $shipment) {
                                foreach ($shipment->getAllTracks() as $tracking_number) {
                                    $tmp .= $tracking_number->getNumber() . " ";
                                }
                            }
                            $item = $tmp;
                        } elseif ($attribute == "address") {
                            $tmp = "";
                            $tmp .= $address->getStreet("1") . " ";
                            $tmp .= $address->getStreet("2") . " ";
                            $tmp .= $address->getStreet("3") . " ";
                            $tmp .= $address->getStreet("4") . " ";
                            $tmp .= $address->getData("city") . " ";
                            $tmp .= $address->getData("region") . " ";
                            $tmp .= $address->getData("postcode") . " ";
                            $tmp .= $address->getData("telephone") . " ";
                            $item = $tmp;
                        } else {
                            if ($attribute == "name") {
                                $item = $address->getData("firstname") . " " .
                                        $address->getData("lastname");
                            } elseif (strpos($attribute, "street_") !== false) {
                                $street = explode("_", $attribute);
                                $item = $address->getStreet($street[1]);
                            } else {
                                $item = $address->getData($attribute);
                            }
                        }
                        break;

                    case "gift":
                        $message = Mage::getModel('giftmessage/message');
                        $gift_message_id = $order->getData("gift_message_id");
                        if (!is_null($gift_message_id)) {
                            $message->load((int) $gift_message_id);
                            if ($attribute == "sender") {
                                $item = $message->getData('sender');
                            } elseif ($attribute == "recipient") {
                                $item = $message->getData('recipient');
                            } elseif ($attribute == "message") {
                                $item = $message->getData('message');
                            }
                        }
                        break;
                }
                $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $this->convertStr($item));
                $lineItem.="{$stripped}\t";
            }

            // endline
            $this->_contentCSV .=$lineItem . "\n";
        }
    }

    private function convertStr($str) {
        $os = $this->getOS();
        if (strpos($os, "Windows") === false) {
        return iconv("UTF-8", "ISO-8859-9", $str);
        }
        else{
            return $str;
        }
    }

    private function getOS() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform = "Unknown OS Platform";
        $os_array = array(
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }

    public function call() {
        $this->_loadOrderObjects();

        $templateLine = Mage::helper("custom_export")->loadTemplate();

        $this->_prepareData($templateLine);

        return $this->_contentCSV;
    }

}
