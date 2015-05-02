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

class AW_Raf_Helper_Config extends Mage_Core_Helper_Abstract
{
    const MAX_EMAILS_PER_LAUNCH = 5;

    const GENERAL_CALCULATE        = 'awraf/general/calculate';
    const GENERAL_COUPONS          = 'awraf/general/coupons';
    const GENERAL_CART_TOTAL       = 'awraf/general/cart_total';
    const GENERAL_MAX_LIMIT        = 'awraf/general/max_limit';
    const GENERAL_MIN_LIMIT        = 'awraf/general/min_limit';

    const REFERRAL_BONUS           = 'awraf/referral/bonus';
    const REFERRAL_BONUS_TYPE      = 'awraf/referral/bonus_type';
    const REFERRAL_BONUS_AMOUNT    = 'awraf/referral/bonus_amount';

    const INVITE_REDIRECT_LINK     = 'awraf/invite/redirect_link';
    const INVITE_ENABLED           = 'awraf/invite/enabled';
    const INVITE_TEMPLATE          = 'awraf/invite/template';
    const INVITE_EMAIL_SENDER      = 'awraf/invite/email_sender';

    public function calculatePurchaseAmount($store = null)
    {
        return Mage::getStoreConfig(self::GENERAL_CALCULATE, $store);
    }

    public function isAllowedWithCoupons($store = null)
    {
        return Mage::getStoreConfig(self::GENERAL_COUPONS, $store);
    }

    public function minTotal($store = null)
    {
        return (float) Mage::getStoreConfig(self::GENERAL_CART_TOTAL, $store);
    }

    public function maxDiscount($store = null)
    {
        return (int) Mage::getStoreConfig(self::GENERAL_MAX_LIMIT, $store);
    }

    public function minDiscount($store = null)
    {
        return (float) Mage::getStoreConfig(self::GENERAL_MIN_LIMIT, $store);
    }

    public function isBonusToReferral($store = null)
    {
        return Mage::getStoreConfig(self::REFERRAL_BONUS, $store);
    }

    public function referralBonusType($store = null)
    {
        return Mage::getStoreConfig(self::REFERRAL_BONUS_TYPE, $store);
    }

    public function referralDiscount($store = null)
    {
        return (float) Mage::getStoreConfig(self::REFERRAL_BONUS_AMOUNT, $store);
    }

    /* Invitation params */
    public function getRedirectTo($store = null)
    {
        return trim(Mage::getStoreConfig(self::INVITE_REDIRECT_LINK, $store))
            ? trim(Mage::getStoreConfig(self::INVITE_REDIRECT_LINK, $store))
            : Mage::getBaseUrl()
        ;
    }

    public function isInviteAllowed($store = null)
    {
        return Mage::getStoreConfig(self::INVITE_ENABLED, $store);
    }

    public function confirmationRequired($store = null)
    {
        if (defined('Mage_Customer_Model_Customer::XML_PATH_IS_CONFIRM')) {
            return Mage::getStoreConfig(Mage_Customer_Model_Customer::XML_PATH_IS_CONFIRM, $store);
        }
        return false;
    }

    public function getNotificationTemplate($store = null)
    {
        return Mage::getStoreConfig(self::INVITE_TEMPLATE, $store);
    }

    public function getEmailSender($store = null)
    {
        return Mage::getStoreConfig(self::INVITE_EMAIL_SENDER, $store);
    }

    public function getSenderData($store = null)
    {
        $sender = $this->getEmailSender($store);
        return array(
            'name' => Mage::getStoreConfig("trans_email/ident_{$sender}/name", $store),
            'email' => Mage::getStoreConfig("trans_email/ident_{$sender}/email", $store)
        );
    }
}