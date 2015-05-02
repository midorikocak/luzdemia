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

class AW_Raf_Model_Total_Invoice_Rafs extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {       
        if ($invoice->getAwrafsBase()) {
            return $this;
        }
        
        $incrementId = $invoice->getOrder()->getIncrementId();
        $orderByReferral = Mage::getModel('awraf/orderref')->load($incrementId, 'order_increment');
        if ($orderByReferral->getId()) {
            $orderInfo = new Varien_Object(Zend_Json::decode($orderByReferral->getOrderInfo()));

        } else if (Mage::helper('awraf')->getAppliedAmount() || Mage::helper('awraf')->getAppliedDiscount()) {
            $orderInfo = new Varien_Object();
            $orderInfo->setData(
                array(
                     'applied_amount'   => Mage::helper('awraf')->getAppliedAmount(),
                     'applied_discount' => Mage::helper('awraf')->getAppliedDiscount(),
                )
            );
        } else {
            return $this;
        }

        $store = $invoice->getOrder()->getStore();

        $discount = $orderInfo->getAppliedAmount() + $orderInfo->getAppliedDiscount();

        foreach ($invoice->getAllItems() as $item) {
            $orderItem = $item->getOrderItem();
            if ($orderItem->isDummy()) {
                continue;
            }

            $orderItemQty = $orderItem->getQtyOrdered();

            if ($orderItemQty) {
                $mult = $item->getData('base_row_total') / $invoice->getOrder()->getBaseSubtotal();
                $grandDiscount = $store->convertPrice($discount * $mult);
                $baseDiscount = $discount * $mult;
                if (is_null($invoice->getId()) || !$invoice->getAwrafWithoutGrandTotal()) {
                    $grandDiscount = Mage::helper('awraf')->convertAmountByCurrencyCode(
                        $grandDiscount,
                        $invoice->getOrder()->getBaseCurrencyCode(),
                        $invoice->getOrder()->getOrderCurrencyCode()
                    );
                    $invoice->setGrandTotal($invoice->getGrandTotal() - $grandDiscount);
                    $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseDiscount);
                }
                $invoice->setAwrafsBase($invoice->getAwrafsBase() + $baseDiscount);
                $invoice->setAwrafs($invoice->getAwrafs() + $grandDiscount);
            }
        }

       $invoice->setAwrafsBase(-1.00 * abs((float) $invoice->getAwrafsBase()));
       $invoice->setAwrafs(-1.00 * abs((float) $invoice->getAwrafs()));
    }
}
