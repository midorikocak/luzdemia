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

class AW_Raf_Model_Rule_Action_Discount extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('awraf/rule_action_discount');
    }

    public function prepare($rule)
    {
        $this
            ->setRuleId($rule->getId())
            ->setCustomerId($rule->getActivity()->getRrId())
            ->setWebsiteId($rule->getActivity()->getWebsiteId())
            ->setTriggerId($rule->getTrigger()->getId())
            ->setDiscount($rule->getAction())
            ->setCreatedAt(Mage::getModel('core/date')->gmtDate())
        ;

        if ($rule->getComment()) {
            $this->setComment($rule->getComment());
        } else {
            $this->setComment(Mage::helper('awraf')->autoMessage($rule->getType()));
        }

        return $this;
    }

    public function createFromObject(Varien_Object $obj)
    {
        $this
            ->setData($obj->getData())
            ->setCreatedAt(Mage::getModel('core/date')->gmtDate())
            ->save()
        ;
        return $this;
    }

    public function updateTriggerCount($count)
    {
        if (!$this->getId()) {
            return false;
        }

        $this->setTrigQty($this->getTrigQty() + $count);
        $this->save();

        return $this;
    }
}