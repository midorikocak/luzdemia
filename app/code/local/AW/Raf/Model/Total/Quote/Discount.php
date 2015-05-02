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

class AW_Raf_Model_Total_Quote_Discount extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    protected $_fetched;
    protected $_margin;
    protected $_total;
    protected $_amountSplit;
    protected $_percentByAddress = array();

    public function __construct()
    {
        $this->setCode('awraf');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $helper = Mage::helper('awraf');
        /* clear raf session data on new quote */
        $this->_monitorQuoteChange($address);
        /* clear raf session data on currecy switch */
        $this->_monitorCurrencySwitch();

        if (!Mage::helper('awraf/config')->isModuleOutputEnabled()) {
            $helper->clearSession();
            return;
        }

        if ($helper->getCustomerId() && ($address->getBaseGrandTotal() > 0)) {

            $helper->clearDiscountByType('amount', $address->getAddressId());
            $helper->clearDiscountByType('discount', $address->getAddressId());

            $this->_splitAmount($address);

            $quoteHelper = Mage::helper('awraf/quote')
                ->setStore(Mage::app()->getStore()->getId())
                ->setAddress($address)
            ;

            if (!$quoteHelper->shouldRender()) {
                if (($helper->getAppliedAmount() || $helper->getAppliedDiscount())
                    && $address->getQuote()->getCouponCode()) {
                    Mage::getSingleton('checkout/session')->addNotice(
                        $helper->__('Refer a friend discount can not be applied with coupons')
                    );
                }
                $helper->clearSession();

                return;
            }

            $address->setAwRafFetch(0);
            $this->_applyBonusDiscount($address);
            $this->_applyFixedDiscount($address);
        }
    }

    protected function _monitorCurrencySwitch()
    {
        $customerSession = Mage::getSingleton('customer/session');
        $helper = Mage::helper('awraf');

        $currentCurrency = Mage::app()->getStore()->getCurrentCurrencyCode();
        if ($customerSession->getAwRafCurrentCurrency()) {
            if ($customerSession->getAwRafCurrentCurrency() != $currentCurrency) {
                if ($helper->getReservedAmount() || $helper->getAppliedAmount()) {
                    $helper->clearSession();
                    Mage::getSingleton('checkout/session')
                        ->addNotice(
                            $helper->__(
                                'Discount for referred friends can be applied per currency.'
                                . 'Please enter discount for referred friends in current currency'
                            )
                        )
                    ;
                }
                $customerSession->setAwRafCurrentCurrency($currentCurrency);
            }
        }

        if (is_null($customerSession->getAwRafCurrentCurrency())) {
            $customerSession->setAwRafCurrentCurrency($currentCurrency);
        }
    }

    protected function _monitorQuoteChange($address)
    {
        $withQuote = Mage::getSingleton('customer/session')->getAwRafCurrentQuote();
        if (is_null($withQuote)) {
            Mage::getSingleton('customer/session')->setAwRafCurrentQuote($address->getQuote()->getId());
        }
        if ($withQuote && $withQuote != $address->getQuote()->getId()) {
            Mage::getSingleton('customer/session')->setAwRafCurrentQuote($address->getQuote()->getId());
            Mage::helper('awraf')->clearSession();
        }
    }

    protected function _splitAmount($address)
    {
        if ($this->_amountSplit) {
            return;
        }

        $helper = Mage::helper('awraf');

        $total = 0;
        foreach ($address->getQuote()->getItemsCollection() as $item) {
            $total += $item->getBaseRowTotal();
        }

        $totalAmount = $helper->getReservedAmount() + $helper->getAppliedAmount();

        foreach ($address->getQuote()->getItemsCollection() as $item) {
            $collected = 0;
            foreach ($item->getQuote()->getAllAddresses() as $addr) {
                if ($total == 0) {
                    $this->_percentByAddress[$addr->getAddressId()] = 1;
                    continue;
                }
                if ((float) $addr->getData('base_subtotal')) {
                    $applyForAddress = $totalAmount * ($addr->getData('base_subtotal') / $total);

                    $this->_percentByAddress[$addr->getAddressId()] = $addr->getBaseSubtotal() / $total;
                    if (($collected + $applyForAddress) > $totalAmount) {
                        $applyForAddress = $total - $collected;
                    }

                    $helper->setDiscountByType('applied_address', $applyForAddress, $addr->getAddressId());

                    $collected += $applyForAddress;
                }
            }
        }
        $this->_amountSplit = true;
    }

    protected function _applyBonusDiscount($address)
    {
        $helper = Mage::helper('awraf');
        $store = Mage::app()->getStore();
        $this->_margin = Mage::helper('awraf/quote')->maxDiscount();
        $discountObject = Mage::getSingleton('awraf/api')->getAvailableDiscount(
            $helper->getCustomerId(), $store->getWebsite()->getId()
        );

        if (!$discountObject->getId()) {
            return;
        }
        //commented: its should be work with PERCENT_DISCOUNT_VALUE and FIXED_DISCOUNT_VALUE
        //by now its work only with FIXED_DISCOUNT_VALUE
        //$discount = null;
        //if ($discountObject->getType() == AW_Raf_Model_Source_ActionType::PERCENT_DISCOUNT_VALUE) {
        $discount = Mage::helper('awraf/quote')->applyOn('base') * $discountObject->getDiscount() / 100;
        if ($this->_margin > 0) {
            $discount = min($this->_margin, $discount);
        }
        $address->setBaseGrandTotal($address->getBaseGrandTotal() - $discount);

        $storeDiscount = Mage::helper('awraf/quote')->applyOn('store') * $discountObject->getDiscount() / 100;
        if ($this->_margin > 0) {
            $allowedAmount = $helper->convertAmountByCurrencyCode(
                $this->_margin, $store->getBaseCurrency()->getCode(), $store->getCurrentCurrency()->getCode()
            );
            $storeDiscount = min($allowedAmount, $storeDiscount);
        }
        $address->setGrandTotal($address->getGrandTotal() - $storeDiscount);

       // } elseif ($discountObject->getType() == AW_Raf_Model_Source_ActionType::FIXED_DISCOUNT_VALUE) {
         //   $discount = $discountObject->getDiscount();
         //   $address->setGrandTotal($address->getGrandTotal() - $store->convertPrice($discount));
          //  $address->setBaseGrandTotal($address->getBaseGrandTotal() - $discount);
        //}

        $helper->setAppliedDiscount($discount);
        $helper->setDiscountByType('discount', $discount, $address->getAddressId());
        $address->setAwRafFetch($store->convertPrice($discount));

        if (!$discount) {
            return;
        }

        if ($this->_margin)
            $this->_margin = $this->_margin - min($this->_margin, $discount);

        return $this;
    }

    protected function _applyFixedDiscount($address)
    {
        $helper = Mage::helper('awraf');
        $store = Mage::app()->getStore();

        if (!$amount = $helper->getDiscountByType('applied_address', $address->getAddressId())) {
            return;
        }

        $error = false;
        if ($this->_margin !== false && round($amount, 2) > round($this->_margin, 2)) {
            $helper->setAppliedAmount($this->_margin);
            if (!$this->_margin) {
                $this->getSession()->addError($helper->__('Discount cannot be applied'));
            }
            $margin = $store->formatPrice($store->convertPrice($this->_margin));
            $this->getSession()->addError($helper->__("Max allowed amount to apply is %s", $margin));
            $error = true;
        }

        if ($helper->getReservedAmount() && !$error) {
            if ((Mage::helper('awraf/quote')->applyOn('base') - $amount) < 0) {
                $this->getSession()->addError($helper->__('Discount can not be greater than subtotal'));
                $helper->setAppliedAmount($helper->getAppliedAmount());

            } else {
                $helper->setAppliedAmount($helper->getAppliedAmount() + $helper->getReservedAmount());
                $this->getSession()->addSuccess($helper->__('Discount has been applied'));
            }
        }

        $appliedAmount = $helper->getAppliedAmount() * $this->_percentByAddress[$address->getAddressId()];

        $helper->setDiscountByType('amount', $appliedAmount, $address->getAddressId());
        $helper->setAppliedAmount($helper->getAppliedAmount());

        $address
            ->setBaseGrandTotal($address->getBaseGrandTotal() - $appliedAmount)
            ->setAwRafFetch($address->getAwRafFetch() + $store->convertPrice($appliedAmount))
            ->setGrandTotal($address->getGrandTotal() - $store->convertPrice($appliedAmount))
        ;
        $helper->setReservedAmount(0);
    }

    public function getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $_result = parent::fetch($address);
        $address
            ->addTotal(
                array(
                    'code'  => $this->getCode(),
                    'title' => Mage::helper('awraf')->__('Discount for referred friends'),
                    'value' => -$address->getAwRafFetch()
                )
            )
        ;
        return $_result;
    }
}