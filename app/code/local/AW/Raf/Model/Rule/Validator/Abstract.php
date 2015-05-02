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

class AW_Raf_Model_Rule_Validator_Abstract extends Mage_Core_Model_Abstract
{
    protected function _getTriggerRestAmountByRule($rule)
    {
        $triggerCollection = Mage::getModel('awraf/trigger')->getCollection();
        $triggerCollection
            ->addFieldToFilter('customer_id', $rule->getActivity()->getRrId())
            ->addFieldToFilter('rule_id', $rule->getId())
            ->setOrder('created_at')
        ;
        if ($rule->getLimit() && $rule->getType() != AW_Raf_Model_Source_RuleType::SIGNUP_VALUE) {
            $triggerCollection->addFieldToFilter('rl_id', $rule->getActivity()->getRlId());
        }
        return $triggerCollection->getFirstItem()->getRestAmount();
    }

    protected function _getActivityAmountTotal($rule)
    {
        $activityCollection = Mage::getModel('awraf/activity')->getCollection();

        $lastTriggerDate = $rule->getActiveFrom();
        if (null !== $rule->getLastTrigerDate()) {
            $lastTriggerDate = $rule->getLastTrigerDate();
        }

        $activityCollection
            ->addFieldToFilter('rr_id', $rule->getActivity()->getRrId())
            ->addFieldToFilter('website_id', (array)$rule->getActivity()->getWebsiteId())
            ->addFieldToFilter('type', (array)$rule->getType())
            ->addTriggerFilter($lastTriggerDate)
            ->addTotalByAmount()
        ;
        if ($rule->getLimit() && $rule->getType() != AW_Raf_Model_Source_RuleType::SIGNUP_VALUE) {
            $activityCollection->addFieldToFilter('rl_id', $rule->getActivity()->getRlId());
        }
        return (float)$activityCollection->getFirstItem()->getTotal();
    }

    protected function _getRuleUsedCount($rule)
    {
        $triggerCollection = Mage::getModel('awraf/trigger')->getCollection();
        $triggerCollection
            ->addFieldToFilter('customer_id', $rule->getActivity()->getRrId())
            ->addFieldToFilter('rule_id', $rule->getId())
            ->addExpressionFieldToSelect('used_count', 'SUM({{trig_qty}})', 'trig_qty')
        ;
        if ($rule->getLimit() && $rule->getType() != AW_Raf_Model_Source_RuleType::SIGNUP_VALUE) {
            $triggerCollection->addFieldToFilter('rl_id', $rule->getActivity()->getRlId());
        }
        return $triggerCollection->getFirstItem()->getUsedCount();
    }

    public function validate($rule)
    {
        $restAmount = (float)$this->_getTriggerRestAmountByRule($rule);

        $activityAmountSum = $this->_getActivityAmountTotal($rule);
        $validateTotal = $activityAmountSum + $restAmount;

        $triggerQty = floor($validateTotal / $rule->getTarget());
        $restAmount = fmod($validateTotal, $rule->getTarget());

        $ruleUsedCount = (int) $this->_getRuleUsedCount($rule);

        if ($rule->getType() != AW_Raf_Model_Source_RuleType::SIGNUP_VALUE
            && $rule->getLimit() && $ruleUsedCount >= $rule->getLimit()
        ) {
            return false;
        }

        if ($rule->getLimit() && $triggerQty > ($rule->getLimit() - $ruleUsedCount)) {
            $_limit = $rule->getLimit() - $ruleUsedCount;
            $restAmount = ($triggerQty - $_limit) * $rule->getTarget();
            $triggerQty = $_limit;
        }

        if (!$rule->getUseRestAmount() && $rule->getType() != AW_Raf_Model_Source_RuleType::SIGNUP_VALUE) {
            $restAmount = 0;
        }

        if ($validateTotal >= $rule->getTarget()) {
            $triggerModel = Mage::getModel('awraf/trigger');
            $triggerModel
                ->addData(
                    array(
                         'customer_id' => $rule->getActivity()->getRrId(),
                         'rl_id'       => $rule->getActivity()->getRlId(),
                         'rule_id'     => $rule->getId(),
                         'trig_qty'    => $triggerQty,
                         'rest_amount' => $restAmount,
                         'created_at'  => Mage::getModel('core/date')->gmtDate(),
                    )
                )
                ->save()
            ;
            $rule->setTrigger($triggerModel);

            return true;
        }
        return false;
    }
}