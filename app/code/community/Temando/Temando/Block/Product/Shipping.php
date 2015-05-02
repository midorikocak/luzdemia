<?php

class Temando_Temando_Block_Product_Shipping extends Mage_Core_Block_Template
{

    /**
     * Initialize block
     *
     */
    protected function _construct()
    {
        $this->setId('temando_shipping');
    }

    public function getCountryId()
    {
        if (Mage::getSingleton('customer/session')->getData('estimate_product_shipping')) {
            return Mage::getSingleton('customer/session')->getData('estimate_product_shipping')->getCountryId();
        }

        return 'AU';
    }

    public function getProductId()
    {
        if (Mage::registry('current_product')) {
            return Mage::registry('current_product')->getId();
        }

        return '0';
    }

    public function getRegionId()
    {
        if (Mage::getSingleton('customer/session')->getData('estimate_product_shipping')) {
            return Mage::getSingleton('customer/session')->getData('estimate_product_shipping')->getRegionId();
        }

        return '';
    }

    public function getCity()
    {
        if (Mage::getSingleton('customer/session')->getData('estimate_product_shipping')) {
            return Mage::getSingleton('customer/session')->getData('estimate_product_shipping')->getCity();
        }

        return '';
    }

    public function getPostcode()
    {
        if (Mage::getSingleton('customer/session')->getData('estimate_product_shipping')) {
            return Mage::getSingleton('customer/session')->getData('estimate_product_shipping')->getPostcode();
        }

        return '';
    }

    public function getPcs()
    {
        if (Mage::getSingleton('customer/session')->getData('estimate_product_shipping')) {
            return Mage::getSingleton('customer/session')->getData('estimate_product_shipping')->getPcs();
        }

        return '';
    }

    public function getCountryOptions() 
    {
	return Mage::helper('temando')->getAllowedCountries();
    }

    public function getQty()
    {
        return '1';
    }


}