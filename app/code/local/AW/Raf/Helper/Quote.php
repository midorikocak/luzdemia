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

class AW_Raf_Helper_Quote extends Mage_Core_Helper_Abstract
{
    protected $_store;
    protected $_address;

    public function minTotalExceeded()
    {
        if (!$minTotal = Mage::helper('awraf/config')->minTotal($this->_store)) {
            return true;
        }
        $total =  $this->_address->getBaseSubtotal();
        if ($total >= $minTotal) {
            return true;
        }
        return false;
    }

    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    public function setAddress($address)
    {
        $this->_address = $address;
        return $this;
    }

    public function couponsAllowed()
    {
        $items = $this->_address->getAllItems();
        foreach ($items as $item) {
            if ($item->getParentItemId()) {
                continue;
            }

            if ($item->getDiscountAmount()) {
                if (!Mage::helper('awraf/config')->isAllowedWithCoupons($this->_store)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function shouldRender()
    {
        return $this->couponsAllowed() && $this->minTotalExceeded();
    }

    public function getQuote()
    {
        return Mage::getSingleton('checkout/session')->getQuote();
    }

    public function maxDiscount()
    {
        $maxDiscount = Mage::helper('awraf/config')->maxDiscount($this->_store);
        if (!$maxDiscount) {
            return false;
        }
        if (!$total = $this->_address->getBaseGrandTotal()) {
            return false;
        }
        $amountToApply = $total - $this->_address->getBaseShippingInclTax() - $this->_address->getBaseTaxAmount();
        return $amountToApply * $maxDiscount / 100;
    }

    public function applyOn($type = 'base')
    {
        $isApplyToSubtotalInclTax = !!Mage::getSingleton('tax/config')->discountTax();
        if ($type == 'base') {
            $result = $this->_address->getBaseGrandTotal() - $this->_address->getBaseShippingInclTax();
            if (!$isApplyToSubtotalInclTax) {
                $result -= $this->_address->getBaseTaxAmount();
            }
        } else if ($type == 'store') {
            $result = $this->_address->getGrandTotal() - $this->_address->getShippingInclTax();
            if (!$isApplyToSubtotalInclTax) {
                $result -= $this->_address->getTaxAmount();
            }
        } else {
            $result = null;
        }
        return $result;
    }

    public function getQuoteAddress()
    {
        if (!$quote = Mage::getSingleton('checkout/session')->getQuote()) {
            return false;
        }
        if ($quote->isVirtual()) {
            return $quote->getBillingAddress();
        } else {
            return $quote->getShippingAddress();
        }
    }
}