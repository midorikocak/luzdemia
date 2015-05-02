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

class AW_Raf_Block_Sales_Order_Totals extends Mage_Core_Block_Template
{
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $order = $parent->getOrder();
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        if (!$customer->getId()) {
            return;
        }

        $baseDiscount = Mage::getModel('awraf/orderref')->getTotalDiscount($order);
        if (!(float)$baseDiscount) {
            return;
        }

        $amount = -1.00 * $baseDiscount;
        $storeDiscount = Mage::helper('awraf')->convertAmountByCurrencyCode(
            $amount, $order->getBaseCurrencyCode(), $order->getOrderCurrencyCode()
        );

        $parent->addTotal(
            new Varien_Object(
                array(
                     'code'       => 'awraf',
                     'value'      => -$storeDiscount,
                     'base_value' => $baseDiscount,
                     'label'      => Mage::helper('awraf')->__('Applied Discount For Referred Friends')
                )
            ),
            'subtotal'
        );

        return $this;
    }
}