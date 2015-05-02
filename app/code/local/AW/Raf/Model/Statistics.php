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

class AW_Raf_Model_Statistics extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('awraf/statistics');
    }

    public function updateStatistics(Varien_Object $activity)
    {        
        $this->setCustomerId($activity->getRrId());
        $this->setWebsiteId($activity->getWebsiteId());
         
        if (!$this->getCustomerId()) {
            return $this;
        }         
        if (!$this->getId()) {
            $this->load($this->getCustomerId(), 'customer_id');
        }
        if ($activity->getType() == AW_Raf_Model_Source_RuleType::SIGNUP_VALUE) {
            $this->setReferralsNumber($this->getReferralsNumber() + $activity->getAmount());

        } elseif ($activity->getType() == AW_Raf_Model_Source_RuleType::ORDER_AMOUNT_VALUE) {
            $this->setAmountPurchased($this->getAmountPurchased() + $activity->getAmount());

        } elseif ($activity->getType() == AW_Raf_Model_Source_RuleType::ORDER_ITEM_QTY_VALUE) {
            $this->setQtyPurchased($this->getQtyPurchased() + $activity->getAmount());
        }
        
        $this
            ->setSpent($this->getSpent() + $activity->getSpent())
            ->setEarned($this->getEarned() + $activity->getEarned())
        ;
        
        $this
            ->setStatsUpdate(Mage::getModel('core/date')->gmtDate())
            ->save()
        ;
    }
}