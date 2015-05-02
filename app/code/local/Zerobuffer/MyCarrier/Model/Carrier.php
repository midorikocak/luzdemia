<?php

class zerobuffer_MyCarrier_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'zerobuffer_mycarrier';

    public function collectRates(
    Mage_Shipping_Model_Rate_Request $request
    ) {
        $result = Mage::getModel('shipping/rate_result');
        /* @var $result Mage_Shipping_Model_Rate_Result */

        $current_time = Mage::getModel('core/date')->date('H:i');
        $sunrise = $this->getConfigData('express_delivery_sunrise');
        $sunset = $this->getConfigData('express_delivery_sunset');
        $date1 = DateTime::createFromFormat('H:i', $current_time);
        $date2 = DateTime::createFromFormat('H:i', $sunrise);
        $date3 = DateTime::createFromFormat('H:i', $sunset);

        $allowed_cities = explode(",", $this->getConfigData('express_delivery_cities'));
        
        if ($date1 > $date2 && $date1 < $date3) {
            $destCity = $request->getDestRegionCode(); //city
            if (in_array(trim($destCity), $allowed_cities)):
                $result->append($this->_getStandardShippingRate());
            endif;
        }

        return $result;
    }

    protected function _getStandardShippingRate() {
        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */

        $rate->setCarrier($this->_code);
        /**
         * getConfigData(config_key) returns the configuration value for the
         * carriers/[carrier_code]/[config_key]
         */
        $expressDeliveryPrice = $this->getConfigData('express_delivery_price');

        $rate->setCarrierTitle($this->getConfigData('title'));

        $rate->setMethod('standand');
        $rate->setMethodTitle('Kurye Ücreti');

        $rate->setPrice(trim(str_replace(",", ".", $expressDeliveryPrice)));
        $rate->setCost(0);

        return $rate;
    }

    public function getAllowedMethods() {
        return array(
            'standard' => 'Standard',
            'express' => 'Express',
            'free_shipping' => 'Free Shipping',
        );
    }

}
