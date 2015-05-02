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

class AW_Raf_Block_Sales_Order_Creditmemo_Totals extends Mage_Sales_Block_Order_Creditmemo_Totals
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

        $qtyRefunded = array();
        if ($this->getCreditmemo()) {
            foreach ($this->getCreditmemo()->getAllItems() as $item) {
                $qtyRefunded[$item->getOrderItemId()] = $item->getQty();
            }
        }

        $currentRowItemDiscountAmount = 0.00;
        $qtyToRefund = Mage::app()->getRequest()->getParam('creditmemo', null);
        foreach ($parent->getOrder()->getAllItems() as $item) {
            $rowItemPercentOfSubtotal = $item->getRowTotal() * 100 / $order->getSubtotal();
            $rowItemRafDiscountAmount = $baseDiscount * $rowItemPercentOfSubtotal / 100;

            if (isset($qtyRefunded[$item->getId()])) {
                $rowItemQtyToRefund = $qtyRefunded[$item->getId()];
            } elseif (is_null($qtyToRefund['items'][$item->getId()])) {
                $rowItemQtyToRefund = $item->getQtyInvoiced() - $item->getQtyRefunded();
            } else {
                $rowItemQtyToRefund = $qtyToRefund['items'][$item->getId()]['qty'];
            }
            $currentRowItemDiscountAmount += $rowItemRafDiscountAmount / $item->getQtyOrdered() * $rowItemQtyToRefund;
        }

        $amount = -1.00 * $currentRowItemDiscountAmount;
        $storeDiscount = Mage::helper('awraf')->convertAmountByCurrencyCode(
            $amount, $order->getBaseCurrencyCode(), $order->getOrderCurrencyCode()
        );

        $parent->addTotal(
            new Varien_Object(
                array(
                     'code'       => 'awraf',
                     'value'      => -$storeDiscount,
                     'base_value' => $currentRowItemDiscountAmount,
                     'label'      => Mage::helper('awraf')->__('Applied Discount For Referred Friends')
                )
            ),
            'subtotal'
        );

        return $this;
    }
}