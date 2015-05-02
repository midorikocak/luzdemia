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

class AW_Raf_Model_Mysql4_Activity extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_activities = array();

    protected function _construct()
    {
        $this->_init('awraf/activity', 'activity_id');
    }

    public function register(Varien_Object $transport)
    {
        foreach ($transport->getTypes() as $type) {
            switch ($type) {
                case AW_Raf_Model_Source_RuleType::SIGNUP_VALUE:
                    $this->_signupRegister($transport);
                    break;
                case AW_Raf_Model_Source_RuleType::ORDER_AMOUNT_VALUE:
                    $this->_orderAmountRegister($transport);
                    break;
                case AW_Raf_Model_Source_RuleType::ORDER_ITEM_QTY_VALUE:
                    $this->_orderQtyRegister($transport);
                    break;
                default:
                    throw new Exception("Unknown activity type {$type}");
            }
        }

        return $this;
    }

    protected function _orderQtyRegister(Varien_Object $transport)
    {
        $invoice = $transport->getInvoice();

        $qty = null;
        foreach ($invoice->getAllItems() as $item) {
            $orderItem = $item->getOrderItem();
            if ($orderItem->isDummy()) {
                continue;
            }

            $qty += $item->getQty();
        }

        $referralObj = $this->_getReferrerFromInvoice($invoice);

        if (is_null($referralObj)) {
            return $this;
        }

        $activityModel = Mage::getModel('awraf/activity')
            ->setAmount($qty)
            ->setWebsiteId($invoice->getStore()->getWebsite()->getId())
            ->setRlId($referralObj->getId())
            ->setRrId($referralObj->getReferrerId())
            ->setRelatedObject($invoice->getId())
            ->setType(AW_Raf_Model_Source_RuleType::ORDER_ITEM_QTY_VALUE)
        ;

        $this->create($activityModel);
    }

    protected function _orderAmountRegister(Varien_Object $transport)
    {
        $invoice = $transport->getInvoice();
        $store = $invoice->getStoreId();

        $priceOnly =
            (
                Mage::helper('awraf/config')->calculatePurchaseAmount($store) ==
                AW_Raf_Model_Source_Calculate::ONLY_PRICE_VALUE
            )
        ;

        $amount = null;
        foreach ($invoice->getAllItems() as $item) {
            $orderItem = $item->getOrderItem();
            if ($orderItem->isDummy()) {
                continue;
            }
            if ($priceOnly) {
                $amount += $item->getBasePrice() * $item->getQty() - abs($item->getBaseDiscountAmount());
            } else {
                $amount += $item->getBasePriceInclTax() * $item->getQty() - abs($item->getBaseDiscountAmount());
            }
        }

        $referralObj = $this->_getReferrerFromInvoice($invoice);

        if (is_null($referralObj)) {
            return $this;
        }

        $activityModel = Mage::getModel('awraf/activity')
            ->setAmount($amount)
            ->setWebsiteId($invoice->getStore()->getWebsite()->getId())
            ->setRlId($referralObj->getId())
            ->setRrId($referralObj->getReferrerId())
            ->setRelatedObject($invoice->getId())
            ->setType(AW_Raf_Model_Source_RuleType::ORDER_AMOUNT_VALUE)
        ;

        $this->create($activityModel);
    }

    /**
     * @param $invoice
     *
     * @return Varien_Object|null
     */
    protected function _getReferrerFromInvoice($invoice)
    {
        $orderByRef = Mage::getModel('awraf/orderref')->load($invoice->getOrder()->getIncrementId(), 'order_increment');

        if (is_null($orderByRef->getId()) || is_null($orderByRef->getCustomerId())) {
            return null;
        }

        $refObject = Mage::getModel('awraf/referral')->load($orderByRef->getReferralId(), 'referral_id');

        if (is_null($refObject->getId()) || is_null($refObject->getCustomerId())) {
            /* quest purchased by link */
            return new Varien_Object(
                array(
                     'id'           => $orderByRef->getReferralId(),
                     'referrer_id'  => $orderByRef->getCustomerId()
                )
            );
        }
        return $refObject;
    }

    protected function _signupRegister(Varien_Object $transport)
    {
        $customer = $transport->getCustomer();

        $store = Mage::app()->getStore($customer->getStoreId());
        /**
         *  $transport->getSaveRafStatus
         *  1 - new customer: create referral, activity and give bonus
         *  2 - new customer not confirmed: create referral with status not confirmed
         *  3 - gets back by confirmation link: generate activity and give bonus, activate referral status
         */
        $status = AW_Raf_Model_Activity::STATUS_SIGNUP_CONFIRMED;

        if ($customer->getConfirmation()) {
            $status = AW_Raf_Model_Activity::STATUS_SIGNUP_NOT_CONFIRMED;
        }
        /* process referral object only if it is not back confirmation link */
        if ($transport->getSaveRafStatus() != AW_Raf_Model_Activity::SIGNUP_BACK_LINK) {
            if ($transport->getReferral()) {
                $referralObj = Mage::getModel('awraf/referral')->load($transport->getReferral());

                if (!$referralObj->getId()) {
                    /* invalidated or expired link */
                    return $this;
                }

                $referralObj
                    ->setReferrerId($transport->getReferrer())
                    ->setEmail($customer->getEmail())
                    ->setCustomerId($customer->getId())
                    ->setWebsiteId($store->getWebsite()->getId())
                    ->setStoreId($store->getId())
                    ->setStatus($status)
                    ->save()
                ;

                Mage::helper('awraf')->unsReferral();

            } else {
                $referral = new Varien_Object();
                $referral
                    ->setReferrerId($transport->getReferrer())
                    ->setEmail($customer->getEmail())
                    ->setCustomerId($customer->getId())
                    ->setWebsiteId($store->getWebsite()->getId())
                    ->setStoreId($store->getId())
                    ->setStatus($status)
                ;
                /* first of all check if customer with such email already invited */
                $referralObj = Mage::getModel('awraf/api')->getReferralByEmail(
                    $referral->getEmail(), $referral->getWebsiteId()
                );

                if (!$referralObj->getId()) {
                    $referralObj = Mage::getModel('awraf/api')->createReferral($referral);
                } else {
                    $referralObj
                        ->setCustomerId($referral->getCustomerId())
                        ->setStatus($status)
                        ->save()
                    ;
                }
            }
        }
        /* activate referral */
        if ($transport->getSaveRafStatus() == AW_Raf_Model_Activity::SIGNUP_BACK_LINK) {
            $referralObj = Mage::getModel('awraf/referral')->load($customer->getId(), 'customer_id');
            if (!$referralObj->getId()) {
                return $this;
            }
            $referralObj
                ->setStatus(AW_Raf_Model_Activity::STATUS_SIGNUP_CONFIRMED)
                ->save()
            ;
        }
        /* create activity object */
        if (!$customer->getConfirmation()) {
            $activityModel = Mage::getModel('awraf/activity')
                ->setAmount(1)
                ->setWebsiteId($store->getWebsite()->getId())
                ->setRlId($referralObj->getId())
                ->setRrId($transport->getReferrer())
                ->setType(AW_Raf_Model_Source_RuleType::SIGNUP_VALUE)
            ;
            $this->create($activityModel);
        }
    }


    public function getActivityByType($type)
    {
        if (isset($this->_activities[$type])) {
            return $this->_activities[$type];
        }

        return false;
    }

    public function getActivities()
    {
        return $this->_activities;
    }

    public function create(Varien_Object $activity)
    {
        $activityModel = Mage::getModel('awraf/activity');
        $activityModel
            ->setData($activity->getData())
            ->setCreatedAt(Mage::getModel('core/date')->gmtDate())
            ->save()
        ;

        Mage::dispatchEvent('aw_raf_activity_create_after', array('activity' => $activityModel));
        Mage::getSingleton('awraf/statistics')->updateStatistics($activityModel);
        $this->_activities[$activityModel->getType()] = $activityModel;
        return $this;
    }
}