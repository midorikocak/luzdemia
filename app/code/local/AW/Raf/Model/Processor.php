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

class AW_Raf_Model_Processor extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('awraf/processor');
    }

    /**
     * @param Varien_Object $transport
     *
     * @throws Exception
     */
    public function process(Varien_Object $transport)
    {
        $types = $transport->getTypes();
        if (!is_array($types) || empty($types)) {
            throw new Exception('No types for processing');
        }

        $registeredActivities = Mage::getResourceModel('awraf/activity')->register($transport);

        $rules = Mage::getModel('awraf/rule')->getCollection()
            ->addTypeFilter($types)
            ->addEnabledFilter()
            ->addActiveFromFilter()
            ->addStoreFilter($transport->getStoreId())
            ->setOrder('main_table.priority')
        ;

        $stop = array();
        foreach ($rules as $rule) {         
            if (isset($stop[$rule->getType()])) {
                $rule->setSkipTransaction(true);
            }
            if (!$activityTypeObj = $registeredActivities->getActivityByType($rule->getType())) {
                continue;
            }

            //need last rule trigger date for check refferal activity
            $lastTrigerDate = Mage::getModel('awraf/trigger')
                ->getCollection()
                ->addMaxCreatedAtColumn()
                ->addFieldToFilter('rule_id', $rule->getId())
                ->addFieldToFilter('customer_id', $activityTypeObj->getRrId())
            ;

            if ($rule->getLimit() && $rule->getType() != AW_Raf_Model_Source_RuleType::SIGNUP_VALUE) {
                $lastTrigerDate->addFieldToFilter('rl_id', $activityTypeObj->getRlId());
            }

            $lastTrigerDate = $lastTrigerDate
                ->getFirstItem()
                ->getCreatedAt()
            ;

            $rule->setLastTrigerDate($lastTrigerDate);

            if ($rule->setActivity($activityTypeObj)->validate()) {
                if ($rule->getStopOnFirst()) {
                    $stop[$rule->getType()] = true;
                }
                if ($rule->getSkipTransaction()) {
                    continue;
                }
                $rule->trigger();
            }
        }
    }
}