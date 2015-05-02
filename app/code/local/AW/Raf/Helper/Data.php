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

class AW_Raf_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONVERT_TO_BASE    = 1;
    const CONVERT_TO_CURRENT = 2;
    const PRECISION          = 2;

    public function getReferafriendUrl()
    {
        return Mage::getUrl('awraf/index/invite', array('_secure' => Mage::app()->getStore(true)->isCurrentlySecure()));
    }

    public function encodeUrlKey($key)
    {
        return Mage::helper('core')->urlEncode(Mage::helper('core')->encrypt($key));
    }

    public function decodeUrlKey($key)
    {
        return Mage::helper('core')->decrypt(Mage::helper('core')->urlDecode($key));
    }

    //class AW_Raf_Model_Source_RuleType hardcode auto message by rule type
    public function autoMessage($type)
    {
        return Mage::getSingleton('awraf/source_ruleType')->getAutoMessage($type);
    }

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function getCustomerId()
    {
        return $this->getCustomer()->getId();
    }

    /* collect totals methods */

    // applied amount
    public function setAppliedAmount($amount)
    {
        $this->_session()->setRafMoneyCustomer($amount);
    }

    public function clearAppliedAmount()
    {
        $this->_session()->setRafMoneyCustomer(0);
    }

    public function getAppliedAmount()
    {
        return $this->_session()->getRafMoneyCustomer();
    }

    public function setReservedAmount($amount)
    {
        $this->_session()->setRafReservedAmount($amount);
    }

    public function getReservedAmount()
    {
        return $this->_session()->getRafReservedAmount();
    }

    /* manage discountes methods */

    public function setDiscountByType($type, $amount, $index = 0)
    {
        $session = $this->_session();
        $discountByAddress = (array) $session->getRafDiscountByAddress();
        $discountByAddress["{$index}_{$type}"] = $amount;
        $session->setRafDiscountByAddress($discountByAddress);
    }

    public function getDiscountByType($type, $index = 0)
    {
        $session = $this->_session();
        $discountByAddress = (array) $session->getRafDiscountByAddress();
        if (isset($discountByAddress["{$index}_{$type}"])) {
            return $discountByAddress["{$index}_{$type}"];
        }
        return false;
    }

    public function clearDiscountByType($type, $index = 0)
    {
        $session = $this->_session();
        $discountByAddress = (array) $session->getRafDiscountByAddress();
        if (isset($discountByAddress["{$index}_{$type}"])) {
            unset($discountByAddress["{$index}_{$type}"]);
        }
        $session->setRafDiscountByAddress($discountByAddress);

        return $this;
    }

    /* manage discounts by type */
    
    public function clearSession()
    {
        $this->_session()->unsRafDiscountByAddress();
        $this->setAppliedDiscount(0);
        $this->setAppliedAmount(0);
        $this->setReservedAmount(0);

        return $this;
    }

    public function setAppliedDiscount($amount)
    {
        $this->_session()->setRafDiscountCustomer($amount);
    }

    public function clearAppliedDiscount()
    {
        $this->_session()->setRafDiscountCustomer(0);
    }

    public function getAppliedDiscount()
    {
        return $this->_session()->getRafDiscountCustomer();
    }

    protected function _session()
    {
        return Mage::getSingleton('customer/session');
    }

    public function isMageLessThan14()
    {
        return version_compare(Mage::getVersion(), '1.4', '<');
    }

    /**
     * Convert price to different directions
     * @param float amount
     * @param array $data
     * @return float
     * @throws Exception
     * @deprecated
     */
    public function convertAmount($amount, array $data)
    {
        $amount = (float) preg_replace("#[^.,0-9]#isu", "", $amount);

        $store = Mage::app()->getStore();
        if (array_key_exists('store', $data)
            && $data['store'] instanceof Mage_Core_Model_Store
            && $data['store']->getId()
        ) {
            $store = $data['store'];
        }

        $options = array();
        $options['direction'] = self::CONVERT_TO_CURRENT;
        if (array_key_exists('direction', $data)) {
            $options['direction'] = $data['direction'];
        }

        $options['floor'] = false;
        if (array_key_exists('floor', $data)) {
            $options['floor'] = $data['floor'];
        }

        $options['format'] = false;
        if (array_key_exists('format', $data)) {
            $options['format'] = $data['format'];
        }

        $options['locale'] = Mage::app()->getLocale();
        if (array_key_exists('locale', $data)) {
            $options['locale'] = $data['locale'];
        }

        return $this->convertAmountByCurrencyCode(
            $amount, $store->getBaseCurrency()->getCode(), $store->getCurrentCurrency()->getCode(), $options
        );
    }

    /**
     * @param       $amount
     * @param       $baseCurrencyCode
     * @param       $currentCurrencyCode
     * @param array $options(direction,format,floor,locale)
     *
     * @return float
     */
    public function convertAmountByCurrencyCode($amount, $baseCurrencyCode, $currentCurrencyCode, $options = array())
    {
        if (!array_key_exists('locale', $options)) {
            $options['locale'] = Mage::app()->getLocale();
        }

        if (!array_key_exists('direction', $options)) {
            $options['direction'] = self::CONVERT_TO_CURRENT;
        }

        if (!array_key_exists('format', $options)) {
            $options['format'] = false;
        }

        if (!array_key_exists('floor', $options)) {
            $options['floor'] = false;
        }

        $amount = (float) preg_replace("#[^.,0-9]#isu", "", $amount);
        $baseCurrencyModel = Mage::getModel('directory/currency')->load($baseCurrencyCode);

        if ($options['direction'] == self::CONVERT_TO_CURRENT) {
            $amount = $baseCurrencyModel->convert($amount, $currentCurrencyCode);
        }

        if ($options['direction'] == self::CONVERT_TO_BASE) {
            $amount = $amount / $baseCurrencyModel->getRate($currentCurrencyCode);
        }

        if ($options['floor']) {
            $amount = (float) floor(round($amount * 100, self::PRECISION)) / 100;
        }

        if ($options['format']) {
            $currentCurrencyModel = Mage::getModel('directory/currency')->load($currentCurrencyCode);
            $amount = $currentCurrencyModel->format($amount, array(), false);
        }

        return $amount;
    }
    
    /**
     * Write referrer id
     * SESSION
     * COOKIE
     * @param int $val
     * @return mixed
     */
    public function setReferrer($val)
    {
        /**
         * set refferer cookie
         * val = decodeUrlKey(Mage::app()->getRequest()->getParam('rel', false));
        **/
        if (!$this->getReferrer()) {
            Mage::getSingleton('customer/session')->setAwrafReferrer($val);
            return Mage::getModel('core/cookie')->set(AW_Raf_Model_Activity::COOKIE_NAME, $val, true);
        }

        return false;
    }

    /**
     * Get referrer from 
     * COOKIE
     * SESSION
     * @return boolean
     */
    public function getReferrer()
    {
        $cookieRef = (int) Mage::getModel('core/cookie')->get(AW_Raf_Model_Activity::COOKIE_NAME);

        if ($cookieRef) {
            return $cookieRef;
        }

        $sessionRef = Mage::getSingleton('customer/session')->getAwrafReferrer();

        if ($sessionRef) {
            return $sessionRef;
        }
        return null;
    }

    /**
     * Clear referrer cookie and session info
     */
    public function unsReferrer()
    {
        Mage::getModel('core/cookie')->delete(AW_Raf_Model_Activity::COOKIE_NAME);
        Mage::getSingleton('customer/session')->setAwrafReferrer(null);

        return $this;
    }

    /**
     * Write referrer id
     * SESSION
     * COOKIE
     * @param int $val
     * @return mixed
     */
    public function setReferral($val)
    {
        if (!$this->getReferral()) {
            Mage::getSingleton('customer/session')->setAwrafReferral($val);
            return Mage::getModel('core/cookie')->set(AW_Raf_Model_Activity::COOKIE_REFERRAL, $val, true);
        }

        return false;
    }

    public function unsReferral()
    {
        Mage::getModel('core/cookie')->delete(AW_Raf_Model_Activity::COOKIE_REFERRAL);
        Mage::getSingleton('customer/session')->setAwrafReferral(null);

        return $this;
    }

    /**
     * Get referral from 
     * COOKIE
     * SESSION
     * @return boolean
     */
    public function getReferral()
    {
        $cookieRef = (int) Mage::getModel('core/cookie')->get(AW_Raf_Model_Activity::COOKIE_REFERRAL);
        if ($cookieRef) {
            return $cookieRef;
        }
        $sessionRef = Mage::getSingleton('customer/session')->getAwrafReferral();
        if ($sessionRef) {
            return $sessionRef;
        }
        return false;
    }

    //generated Broadcastlink
    /**
     * @param $ref = null
     * @param $rel = null
     *
     * @return string
     */
    public function getReferrerLink($ref = null, $rel = null)
    {
        $urlKey = null;
        if ($ref) {
            $urlKey = $this->encodeUrlKey($ref);
        } else {
            $session = Mage::getSingleton('customer/session');
            if ($session->isLoggedIn()) {
                $urlKey = $this->encodeUrlKey($session->getCustomer()->getId());
            }
        }

        if ($urlKey) {
            $queryData = array('ref' => $urlKey);
            if ($rel) {
                $queryData['rel'] = $this->encodeUrlKey($rel);
            }
            return Mage::getUrl('', array(
                '_query' => $queryData,
                '_secure' => Mage::app()->getStore(true)->isCurrentlySecure(),
                '_store' => Mage::app()->getStore()->getId(),
                '_store_to_url' => true)
            );
        }

        return Mage::getBaseUrl();
    }
}
