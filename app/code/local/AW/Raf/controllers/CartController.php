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

class AW_Raf_CartController extends Mage_Core_Controller_Front_Action
{
    public function createCouponAction()
    {
        if (!$this->_validate()) {
            return $this->_redirectReferer();
        }

        $customer = Mage::helper('awraf')->getCustomer();
        $post = Mage::app()->getRequest()->getPost();

        if ($post['remove'] == 1) {
            Mage::helper('awraf')->clearAppliedAmount();
            Mage::helper('awraf')->setReservedAmount(0);
            $quote = Mage::helper('awraf/quote')->getQuote();
            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote
                ->collectTotals()
                ->save()
            ;
            Mage::getSingleton('checkout/session')->addSuccess($this->__('Discount has been cancelled'));
            return $this->_redirectReferer();
        }

        $store = Mage::app()->getStore();
        $amount = Mage::helper('awraf')
            ->convertAmountByCurrencyCode(
                $post['awraf-amount'],
                $store->getBaseCurrency()->getCode(),
                $store->getCurrentCurrency()->getCode(),
                array(
                     'direction' => AW_Raf_Helper_Data::CONVERT_TO_BASE,
                     'floor'     => true
                )
            )
        ;

        if ($amount) {
            $availableAmount = Mage::getModel('awraf/api')
                ->getAvailableAmount(
                    $customer->getId(),
                    Mage::app()->getWebsite()->getId()
                )
            ;
            $margin = (float)round(
                $availableAmount - Mage::helper('awraf')->getAppliedAmount(),
                AW_Raf_Helper_Data::PRECISION
            );

            if (floatval($amount) <= floatval($margin)) {
                Mage::helper('awraf')->setReservedAmount($amount);
                $quote = Mage::helper('awraf/quote')->getQuote();
                $quote->getShippingAddress()->setCollectShippingRates(true);
                $quote
                    ->collectTotals()
                    ->save()
                ;
                /**
                 * Mage::getSingleton('checkout/session')->addSuccess(
                 *     Mage::helper('awraf')->__('Discount has been successfully applied')
                 * );
                 * message from class AW_Raf_Model_Total_Quote_Discount
                 */
            } else {
                Mage::getSingleton('checkout/session')->addError($this->__('Not enough money to apply'));
            }
        } else {
            $currentCurrencyModel = Mage::getModel('directory/currency')->load($store->getCurrentCurrency()->getCode());
            $amount = $currentCurrencyModel->format(0.01, array(), false);
            Mage::getSingleton('checkout/session')->addError($this->__('Minimal amount to apply is %s', $amount));
        }

        return $this->_redirectReferer();
    }

    protected function _validate()
    {
        $customer = Mage::helper('awraf')->getCustomer();
        if (!$customer->getId()) {
            Mage::getSingleton('checkout/session')->addError($this->__('Only logged in customers can apply discounts'));
            return false;
        }
        $post = Mage::app()->getRequest()->getPost();
        if (!array_key_exists('remove', $post)) {
            Mage::getSingleton('checkout/session')->addError($this->__('Incorrect post data'));
            return false;
        }

        if ($post['remove'] != 1 && !array_key_exists('awraf-amount', $post)) {
            Mage::getSingleton('checkout/session')->addError($this->__('Incorrect post data'));
            return false;
        }

        return true;
    }
}