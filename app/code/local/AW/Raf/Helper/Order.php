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


class AW_Raf_Helper_Order extends Mage_Core_Helper_Abstract
{
    public function getAppliedRafAmounts($order)
    {
        $quoteAddressId = null;
        foreach ($order->getAllItems() as $item) {
            if ($item->getParentItem()) {
                continue;
            }
            $quoteAddressId = Mage::getModel('sales/quote_address_item')
                ->load($item->getQuoteItemId())->getQuoteAddressId()
            ;
            if (!$quoteAddressId) {
                $appliedAmount = Mage::helper('awraf')->getAppliedAmount();
                $appliedDiscount = Mage::helper('awraf')->getAppliedDiscount();

            } else {
                $appliedAmount = Mage::helper('awraf')->getDiscountByType('amount', $quoteAddressId);
                $appliedDiscount = Mage::helper('awraf')->getDiscountByType('discount', $quoteAddressId);
            }
            break;
        }

        return array(
            'amount' => $appliedAmount,
            'discount' => $appliedDiscount,
            'quote_address' => $quoteAddressId
        );
    }

    public function getReferralInfo($order, $orderInfo)
    {
        $referralCustomerId = $order->getCustomerId();
        $websiteId = $order->getStore()->getWebsite()->getId();

        $discountObject = false;
        $referralModel = null;

        if ($referralCustomerId) {
            $quote = $order->getQuote();
            if ($quote && $quote->getData('checkout_method') === Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER) {
                $customerEmail = $order->getCustomerEmail();
                $referralModel = Mage::getSingleton('awraf/api')->getReferralByEmail($customerEmail, $websiteId);
                if ($referralModel->getId()) {
                    $referralModel->setCustomerId($referralCustomerId)->save();
                    Mage::getModel('awraf/api')->addBonusToReferral($referralModel);
                }
            } else {
                $referralModel = Mage::getSingleton('awraf/api')->getReferral($referralCustomerId, $websiteId);
            }
            $discountObject = Mage::getSingleton('awraf/api')->getAvailableDiscount($referralCustomerId, $websiteId);
            $orderInfo->setAppliedDiscountInfo($discountObject->toArray());
        } elseif (Mage::helper('awraf')->getReferral()) {
            $referralModel = Mage::getModel('awraf/referral')->load(Mage::helper('awraf')->getReferral());
        } else {
            $referralModel = Mage::getModel('awraf/referral')->load($order->getCustomerEmail(), 'email');
        }

        if (!$referralCustomerId && !$referralModel->getId() && Mage::helper('awraf')->getReferrer()) {
            $customer = Mage::getModel('customer/customer')->setWebsiteId($websiteId)->loadByEmail(
                $order->getCustomerEmail()
            );
            if ($customer->getId() == Mage::helper('awraf')->getReferrer()) {
                return array();
            }
            $referralModel = Mage::getModel('awraf/api')
                ->createReferral(
                    array(
                         'referrer_id' => Mage::helper('awraf')->getReferrer(),
                         'website_id'  => Mage::app()->getStore()->getWebsite()->getId(),
                         'store_id'    => Mage::app()->getStore()->getId(),
                         'email'       => trim($order->getCustomerEmail()),
                    )
                )
            ;
        }

        return array(
            'customer'     => $referralModel->getReferrerId(),
            'referral'     => $referralModel,
            'discount_obj' => $discountObject,
        );
    }
}