<?php


class Temando_Temando_Block_Product_Shipping_Methods extends Mage_Core_Block_Template
{

    public function getEstimateRates()
    {
	$rates = Mage::registry('product_rates');
	return $rates;
        //return Mage::registry('product_rates');
    }


    public function getCarrierName($carrierCode)
    {
        if ($name = Mage::getStoreConfig('carriers/'.$carrierCode.'/title')) {
            return $name;
        }
        return $carrierCode;
    }

    public function formatPrice($price)
    {
        return Mage::app()->getStore()->convertPrice($price, true);
    }
}
