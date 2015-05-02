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

class AW_Raf_Model_Observer
{
    /**
     * Signup activity if referrer cookie is set
     * @param type $observer
     *
     * @return void
     */
    public function customerSaveBefore($observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if (
            !Mage::helper('awraf/config')->isModuleOutputEnabled()
            || !Mage::helper('awraf/config')->isInviteAllowed()
        ) {
            return;
        }

        if (
            !$customer->getId()
            && !Mage::helper('awraf/checkout')->isCheckoutPage()
            && !$customer->isConfirmationRequired()
        ) {
            return $this;
        }

        $rafActivityStatus = null;

        /* customer gets back by confirmation link */
        if ($customer->getOrigData('confirmation') && !$customer->getConfirmation()) {
            $rafActivityStatus = AW_Raf_Model_Activity::SIGNUP_BACK_LINK;
        } else if (
            $customer->getUpdatedAt() == $customer->getCreatedAt()
            && (
                Mage::getSingleton('customer/session')->isLoggedIn() ||
                Mage::helper('awraf/checkout')->isCheckoutPage()
            )
        ) {
            $rafActivityStatus = AW_Raf_Model_Activity::SIGNUP_NEW_VALID;
        } else if (
            $customer->getUpdatedAt() == $customer->getCreatedAt() &&
            Mage::helper('awraf/config')->confirmationRequired($customer->getStoreId())
        ) {
            /* new registered customer confirmation required */
            $rafActivityStatus = AW_Raf_Model_Activity::SIGNUP_NEW_CONFIRM;
        }

        if (is_null($rafActivityStatus)) {
            return $this;
        }

        $referral = Mage::getModel('awraf/referral')->load($customer->getId(), 'customer_id');
        if (!$referral->getId()) {
            if (!$customer->getOrigData()) {
                $email = Mage::getModel('customer/customer')->load($customer->getId())->getEmail();
            } else {
                $email = $customer->getOrigData('email');
            }
            $referral = Mage::getModel('awraf/referral')->load($email, 'email');
        }
        if ($referral->getId()) {
            $referral
                ->setEmail($customer->getEmail())
                ->setCustomerId($customer->getId())
                ->setStatus($rafActivityStatus)
                ->save()
            ;
            Mage::getModel('awraf/api')->addBonusToReferral($referral);
        }

        /* if there is no referrer cookie and it is not back confirmation */
        if (
            (!$referrer = Mage::helper('awraf')->getReferrer()) &&
            ($rafActivityStatus != AW_Raf_Model_Activity::SIGNUP_BACK_LINK)
        ) {
            return $this;
        }

        $transport = new Varien_Object();
        $transport
            ->setCustomer($customer)
            ->setSaveRafStatus($rafActivityStatus)
            ->setStoreId(Mage::app()->getStore()->getId())
            ->setTypes(array(AW_Raf_Model_Source_RuleType::SIGNUP_VALUE))
            ->setReferrer($referrer)
            ->setReferral(Mage::helper('awraf')->getReferral())
        ;

        Mage::getModel('awraf/processor')->process($transport);
    }

    public function customerSaveAfter($observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if (
            !Mage::helper('awraf/config')->isModuleOutputEnabled()
            || !Mage::helper('awraf/config')->isInviteAllowed()
        ) {
            return $this;
        }
        if (
            !$customer->getId()
            && !Mage::helper('awraf/checkout')->isCheckoutPage()
            && !$customer->isConfirmationRequired()
        ) {
            return $this;
        }
        if ($customer->getOrigData()) {
            return $this;
        }

        $referral = Mage::getModel('awraf/referral')->load($customer->getId(), 'customer_id');
        if (!$referral->getId()) {
            if (!$customer->getOrigData()) {
                $email = Mage::getModel('customer/customer')->load($customer->getId())->getEmail();
            } else {
                $email = $customer->getOrigData('email');
            }
            $referral = Mage::getModel('awraf/referral')->load($email, 'email');
        }
        if (!$referral->getId()) {
            return $this;
        }
        $referral->setCustomerId($customer->getId())->save();
        Mage::getModel('awraf/api')->addBonusToReferral($referral);
        return $this;
    }

    /**
     * Move linked referral to guest referral
     */
    public function customerDeleteBefore($observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        $referralModel = Mage::getSingleton('awraf/api')->getReferral($customer->getId(), $customer->getWebsiteId());
        if ($referralModel->getId()) {
            $referralModel
                ->setCustomerId(null)
                ->setStatus(AW_Raf_Model_Activity::STATUS_GUEST)
                ->save()
            ;
        }
    }

    public function quoteSaveAfter($observer)
    {
        if (!Mage::helper('awraf/config')->isModuleOutputEnabled()) {
            return $this;
        }

        if (!$observer->getQuote()->hasItems()) {
            Mage::helper('awraf')->clearAppliedAmount();
        }
    }

    public function coreBlockAbstractToHtmlAfter($observer)
    {
        if ($observer->getBlock() instanceof Mage_Checkout_Block_Cart_Coupon) {
            if (!Mage::helper('awraf/config')->isModuleOutputEnabled()) {
                return;
            }

            $block = Mage::app()->getLayout()->createBlock('awraf/checkout_cart_discount');
            $observer->getTransport()->setHtml($observer->getTransport()->getHtml() . $block->toHtml());
        }
    }

    public function frontControllerPredispatch($observer)
    {
        if (!Mage::helper('awraf/config')->isModuleOutputEnabled()) {
            return;
        }

        $rel = Mage::app()->getRequest()->getParam('rel', false);
        if ($rel) {
            $key = (int) Mage::helper('awraf')->decodeUrlKey($rel);
            if ($key) {
                Mage::helper('awraf')->setReferral($key);
            }
        }

        $ref = Mage::app()->getRequest()->getParam('ref', false);
        if ($ref) {
            $key = (int) Mage::helper('awraf')->decodeUrlKey($ref);
            if ($key) {
                Mage::helper('awraf')->setReferrer($key);
                $redirectUrl = Mage::helper('awraf/config')->getRedirectTo(Mage::app()->getStore()->getId());
                if ($redirectUrl) {
                    Mage::app()
                        ->getResponse()
                        ->setRedirect($redirectUrl)
                        ->sendResponse()
                    ;
                    $observer->getControllerAction()->setFlag(
                        '', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true
                    );
                }
            }
        }
    }

    public function invoiceLoadAfter($observer)
    {
        Mage::getModel('awraf/total_invoice_rafs')->collect($observer->getInvoice()->setAwrafWithoutGrandTotal(true));
    }

    public function paypalPrepare($observer)
    {
        if (!Mage::helper('awraf/config')->isModuleOutputEnabled()) {
            return;
        }
        $paypalCart = $observer->getEvent()->getPaypalCart();
        $appliedMoney =  Mage::helper('awraf')->getAppliedDiscount() + Mage::helper('awraf')->getAppliedAmount();

        if ($paypalCart && $appliedMoney) {
            $paypalCart->updateTotal(
                Mage_Paypal_Model_Cart::TOTAL_DISCOUNT,
                $appliedMoney,
                Mage::helper('awraf')->__('Discount for referred friends')
            );
        }
    }

    /**
     * Order events
     * @param Varien_Object $observer
     */
    public function orderPlaceAfter($observer)
    {
        $order = $observer->getOrder();
        if (!Mage::helper('awraf/config')->isModuleOutputEnabled()) {
            return;
        }

        $orderByRef = Mage::getModel('awraf/orderref')
            ->load($order->getIncrementId(), 'order_increment')
        ;
        if (!is_null($orderByRef->getId())) {
            return;
        }

        /* get applied amounts from session */
        $applied = Mage::helper('awraf/order')->getAppliedRafAmounts($order);

        $websiteId = $order->getStore()->getWebsite()->getId();

        $orderInfo = new Varien_Object(
            array(
                 'applied_amount'   => $applied['amount'],
                 'applied_discount' => $applied['discount']
            )
        );

        $referralInfo = Mage::helper('awraf/order')->getReferralInfo($order, $orderInfo);

        if (empty($referralInfo)) {
            return;
        }

        $info = Mage::getModel('awraf/orderref')
            ->setOrderIncrement($order->getIncrementId())
            ->setCustomerId($referralInfo['customer'])
            ->setReferralId($referralInfo['referral']->getId())
            ->setWebsiteId($websiteId)
            ->setCreatedAt(Mage::getModel('core/date')->gmtDate())
            ->setOrderInfo($orderInfo->toJSON())
            ->save()
        ;

        $customer =  Mage::helper('awraf')->getCustomer()->getId();
        if ($info->getId() && $applied['amount'] && $customer) {

            $transport = new Varien_Object();
            $transport
                ->setCustomerId($customer)
                ->setWebsiteId($websiteId)
                ->setComment(Mage::helper('awraf')->autoMessage(AW_Raf_Model_Source_RuleType::SPENT_DISCOUNT))
                ->setDiscount(-$applied['amount'])
                ->setTrigger(AW_Raf_Model_Source_ActionType::TRANSACTION_TRIGGER)
                ->setType(AW_Raf_Model_Source_ActionType::FIXED_DISCOUNT_VALUE)
            ;

            Mage::getModel('awraf/api')->add($transport);

            Mage::getSingleton('awraf/statistics')->updateStatistics(
                new Varien_Object(
                    array(
                         'rr_id' => $customer,
                         'spent' => $applied['amount']
                    )
                )
            );
        }

        if ($applied['discount'] && $referralInfo['discount_obj']) {
            $referralInfo['discount_obj']->updateTriggerCount(1);
        }
    }

    /**
     * Order amount activity
     * Order qty activity
     * @param type $observer
     */
    public function invoiceSaveAfter($observer)
    {
        if (!Mage::helper('awraf/config')->isModuleOutputEnabled()) {
            return;
        }

        if (null === $observer->getInvoice()->getOrigData('updated_at')) {
            $orderByRef = Mage::getModel('awraf/orderref')
                ->loadByOrderIncrement($observer->getInvoice()->getOrder()->getIncrementId())
            ;
            if (!$orderByRef->getId()) {
                $emulation = new Varien_Object(
                    array(
                         'order' => $observer->getInvoice()->getOrder()
                    )
                );
                $this->orderPlaceAfter($emulation);
            }

            $transport = new Varien_Object();
            $transport
                ->setInvoice($observer->getInvoice())
                ->setStoreId($observer->getInvoice()->getStoreId())
                ->setTypes(
                    array(
                         AW_Raf_Model_Source_RuleType::ORDER_AMOUNT_VALUE,
                         AW_Raf_Model_Source_RuleType::ORDER_ITEM_QTY_VALUE
                    )
                )
            ;

            Mage::getModel('awraf/processor')->process($transport);
        }
    }
}