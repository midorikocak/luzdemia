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


class AW_Raf_Block_Checkout_Total extends Mage_Checkout_Block_Total_Default
{
    protected $_template = 'aw_raf/checkout/total.phtml';

    public function getRafDiscount()
    {
        $discount = $this->getTotal()->getValue();
        if (!$discount) {
            $discount = 0;
            $savedRafDiscount = Mage::getSingleton('customer/session')->getRafDiscountByAddress();
            $addressId = '';
            if ($this->helper('awraf/quote')->getQuoteAddress()) {
                $addressId = $this->helper('awraf/quote')->getQuoteAddress()->getId();
            }
            if (is_array($savedRafDiscount) && array_key_exists($addressId . '_amount', $savedRafDiscount) &&
                $this->helper('awraf')->getAppliedAmount()
            ) {
                $discount += $savedRafDiscount[$addressId . '_amount'];
            }
            if (is_array($savedRafDiscount) && array_key_exists($addressId . '_discount', $savedRafDiscount)) {
                $discount += $savedRafDiscount[$addressId . '_discount'];
            }
            $store = Mage::app()->getStore();
            $discount = Mage::helper('awraf')->convertAmountByCurrencyCode(
                $discount, $store->getBaseCurrency()->getCode(), $store->getCurrentCurrency()->getCode()
            );
        }
        return $discount;
    }
}