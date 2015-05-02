<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Raf
 * @version    2.1.5
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Raf_Block_Checkout_Cart_Discount extends Mage_Core_Block_Template
{
    protected $_template = 'aw_raf/checkout/cart/discount.phtml';

    public function getAvailableAmount($baseAmount = false, $format = true)
    {
        $availableAmount = Mage::getSingleton('awraf/api')->getAvailableAmount(
            Mage::helper('awraf')->getCustomerId(),
            Mage::app()->getWebsite()->getId()
        );
        if ($baseAmount) {
            return $availableAmount;
        }

        $amount = (float)round(
            $availableAmount - Mage::helper('awraf')->getAppliedAmount(),
            AW_Raf_Helper_Data::PRECISION
        );
        $store = Mage::app()->getStore();
        return Mage::helper('awraf')->convertAmountByCurrencyCode(
            max(0, $amount),
            $store->getBaseCurrency()->getCode(),
            $store->getCurrentCurrency()->getCode(),
            array(
                'format' => $format,
                'floor'  => true
            )
        );
    }
    
    public function getNumericAmount()
    {
        return $this->getAvailableAmount(false, null);
    }
 
    public function cancelAllowed()
    {
        return $this->helper('awraf')->getAppliedAmount();
    }

    public function getMaxDiscount()
    {
        return Mage::helper('awraf/config')->maxDiscount(Mage::app()->getStore()->getId());
    }

    public function discountAllowed()
    {
        $store = Mage::app()->getStore();

        $address = Mage::helper('awraf/quote')->getQuoteAddress();

        if (!$address) {            
            return false;
        }

        if (!Mage::helper('awraf/quote')->setStore($store->getId())
                        ->setAddress($address)
                        ->shouldRender()) {
            return false;
        }

        $minDiscount = Mage::helper('awraf/config')->minDiscount($store->getId());

        $availableAmount = $this->getAvailableAmount(true);

        if ((!$minDiscount || $minDiscount < 0) && (float)$availableAmount > 0) {
            return true;
        }

        if ($minDiscount <= (float)$availableAmount) {
            return true;
        }

        return false;
    }

}
