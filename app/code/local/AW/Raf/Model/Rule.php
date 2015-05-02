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

class AW_Raf_Model_Rule extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('awraf/rule');
    }

    /**
     * @return boolean
     */
    public function validate()
    {
        $validator = $this->getRuleValidatorModel();
        if ($validator->validate($this)) {
            return true;
        }

        return false;
    }

    /**
     * @return AW_Raf_Model_Rule obj
     */
    public function trigger()
    {
        $transaction = Mage::getModel('core/resource_transaction');
        $actionTypeModel = $this->getActionTypeModel();
        $result = $actionTypeModel->prepare($this);

        if (!is_array($result)) {
            $result = array($result);
        }

        foreach ($result as $object) {
            if (!$object instanceof Mage_Core_Model_Abstract) {
                continue;
            }

            $transaction->addObject($object);
        }

        $transaction->save();
        if (method_exists($actionTypeModel, 'updateStats')) {
            $actionTypeModel->updateStats();
        }

        return $this;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getActionTypeModel()
    {
        $actionTypeValue = $this->getActionType();
        $actionTypeInstance = Mage::getModel('awraf/source_actionType')->getActionInstanceByTypeValue($actionTypeValue);

        if (null !== $actionTypeInstance) {
            $actionTypeModel = Mage::getModel($actionTypeInstance);
        }

        if (!isset($actionTypeModel) || !($actionTypeModel instanceof Mage_Core_Model_Abstract)) {
            throw new Exception("Action source instance not found");
        }

        return $actionTypeModel;
    }

    /**
     * 
     * @return array
     * @throws Exception
     */
    public function getRuleValidatorModel()
    {
        $ruleTypeValue = $this->getType();
        $ruleTypeInstance = Mage::getModel('awraf/source_ruleType')->getValidatorInstanceByTypeValue($ruleTypeValue);

        if (null !== $ruleTypeInstance) {
            $ruleValidatorModel = Mage::getModel($ruleTypeInstance);
        }

        if (!isset($ruleValidatorModel) || !($ruleValidatorModel instanceof AW_Raf_Model_Rule_Validator_Abstract)) {
            throw new Exception("Validator source instance not found");
        }

        return $ruleValidatorModel;
    }
}