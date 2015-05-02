<?php

class Temando_Temando_Block_Cart_Shipping extends Mage_Checkout_Block_Cart_Shipping
{


    public function getCityActive()
    {
        return (bool)Mage::getStoreConfig('carriers/temando/active') || parent::getCityActive();
    }

    /**
     * Check if one of carriers require state/province
     *
     * @return bool
     */
    public function isStateProvinceRequired()
    {
        return (bool)Mage::getStoreConfig('carriers/temando/active') || parent::isStateProvinceRequired();
    }

    /**
     * Check if one of carriers require city
     *
     * @return bool
     */
    public function isCityRequired()
    {
        return (bool)Mage::getStoreConfig('carriers/temando/active') || parent::isCityRequired();
    }

    /**
     * Check if one of carriers require zip code
     *
     * @return bool
     */
    public function isZipCodeRequired()
    {
        return (bool)Mage::getStoreConfig('carriers/temando/active') || parent::isZipCodeRequired();
    }

    public function getEstimatePostcode()
    {
        $return = parent::getEstimatePostcode();
        if (!$return && Mage::helper('temando')->getSessionPostcode()) {
            $return = Mage::helper('temando')->getSessionPostcode();
        }

        return $return;
    }

    public function getEstimateCity()
    {
        $return = parent::getEstimateCity();
        if (!$return && Mage::helper('temando')->getSessionCity()) {
            $return = Mage::helper('temando')->getSessionCity();
        }

        return $return;
    }

    public function getEstimateRegionId()
    {
        $return = parent::getEstimateRegionId();
        if (!$return && Mage::helper('temando')->getSessionRegionId()) {
            $return = Mage::helper('temando')->getSessionRegionId();
        }

        return $return;
    }

    public function getEstimateRegion()
    {
        $return = parent::getEstimateRegion();
        if (!$return && Mage::helper('temando')->getSessionRegion()) {
            $return = Mage::helper('temando')->getSessionRegion();
        }

        return $return;
    }

}