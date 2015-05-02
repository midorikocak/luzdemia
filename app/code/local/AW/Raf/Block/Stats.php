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


class AW_Raf_Block_Stats extends Mage_Core_Block_Template
{

    protected function _construct()
    {
        parent::_construct();
        $this->_prepareCollection();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    protected function _toHtml()
    {
        $this->getChild('awraf.stats.pager')->setCollection($this->getInvites());
        return parent::_toHtml();
    }

    protected function _prepareCollection()
    {
        $invites = Mage::getResourceModel('awraf/referral_collection')
            ->addFieldToFilter('main_table.referrer_id', $this->getCustomerId())
            ->addFieldToFilter('main_table.website_id', array('eq' => Mage::app()->getWebsite()->getId()))
            ->setOrder('main_table.referral_id')
            ->joinRafActivityTable()
            ->groupBy('main_table.referral_id')
            ->addExpressionFieldToSelect(
                'items_purchased',
                'SUM(IF({{activity.type}} = ' . AW_Raf_Model_Source_RuleType::ORDER_ITEM_QTY_VALUE . ', amount, NULL))',
                'activity.type'
            )
            ->addExpressionFieldToSelect(
                'amount_purchased',
                'SUM(IF({{activity.type}} = ' . AW_Raf_Model_Source_RuleType::ORDER_AMOUNT_VALUE . ', amount, NULL))',
                'activity.type'
            )
        ;
        $this->setInvites($invites);

        return $this;
    }

    public function getActiveBalance()
    {
        $amount = Mage::getSingleton('awraf/api')->getAvailableAmount(
            Mage::helper('awraf')->getCustomerId(),
            Mage::app()->getWebsite()->getId()
        );
        return $this->formatAmount($amount);
    }

    public function isConfirmed($invite)
    {
        return in_array(
            $invite->getStatus(),
            array(
                 AW_Raf_Model_Activity::STATUS_SIGNUP_CONFIRMED,
                 AW_Raf_Model_Activity::SIGNUP_BACK_LINK,
            )
        );
    }

    public function getActiveDiscount()
    {
        $availableDiscount = Mage::getModel('awraf/api')
            ->getAvailableDiscount(
                Mage::helper('awraf')->getCustomerId(),
                Mage::app()->getWebsite()->getId()
            )
        ;
        return $availableDiscount->getDiscount();
    }

    public function formatAmount($amount)
    {
        $store =  Mage::app()->getStore();
        return Mage::helper('awraf')
            ->convertAmountByCurrencyCode(
                $amount,
                $store->getBaseCurrency()->getCode(),
                $store->getCurrentCurrency()->getCode(),
                array(
                    'format' => true
                )
            )
        ;
    }

    public function isInviteAllowed()
    {
        return Mage::helper('awraf/config')->isInviteAllowed();
    }

    public function getCustomerId()
    {
        return Mage::getSingleton('customer/session')->getCustomer()->getId();
    }

    public function getConverted($value)
    {
        return Mage::app()->getStore()->formatPrice($value);
    }
}
