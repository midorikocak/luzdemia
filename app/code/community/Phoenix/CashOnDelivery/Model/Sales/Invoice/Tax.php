<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Phoenix
 * @package    Phoenix_CashOnDelivery
 * @copyright  Copyright (c) 2010 - 2013 PHOENIX MEDIA GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Model_Sales_Invoice_Tax extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $codTax        = 0;
        $baseCodTax    = 0;
        $order         = $invoice->getOrder();
        $includeCodTax = true;

        if ($order->getPayment()->getMethodInstance()->getCode() != 'phoenix_cashondelivery') {
            return $this;
        }

        /**
         * Check Cod amount in previus invoices
         */
        foreach ($order->getInvoiceCollection() as $previousInvoice) {

            if ($previousInvoice->getCodFee() && !$previousInvoice->isCanceled()) {
                $includeCodTax = false;
            }
        }

        if ($includeCodTax) {
            $codTax     = $order->getCodTaxAmount();
            $baseCodTax = $order->getBaseCodTaxAmount();

            $invoice->setCodTaxAmount($order->getCodTaxAmount());
            $invoice->setBaseCodTaxAmount($order->getBaseCodTaxAmount());

            $invoice->getOrder()->setCodTaxAmountInvoiced($codTax);
            $invoice->getOrder()->setBaseCodTaxAmountInvoiced($baseCodTax);
        }

        /**
         * Not isLast() invoice case handling
         * totalTax adjustment
         * check Mage_Sales_Model_Order_Invoice_Total_Tax::collect()
         */
        $allowedTax     = $order->getTaxAmount()     - $order->getTaxInvoiced();
        $allowedBaseTax = $order->getBaseTaxAmount() - $order->getBaseTaxInvoiced();

        $totalTax       = $invoice->getTaxAmount();
        $baseTotalTax   = $invoice->getBaseTaxAmount();

        if (!$invoice->isLast() && $allowedTax > $totalTax) {
            $newTotalTax           = min($allowedTax, $totalTax + $codTax);
            $newBaseTotalTax       = min($allowedBaseTax, $baseTotalTax + $baseCodTax);

            $invoice->setTaxAmount($newTotalTax);
            $invoice->setBaseTaxAmount($newBaseTotalTax);

            $invoice->setGrandTotal($invoice->getGrandTotal() - $totalTax + $newTotalTax);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseTotalTax + $newBaseTotalTax);
        }

        return $this;
    }
}