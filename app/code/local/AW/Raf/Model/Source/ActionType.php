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

class AW_Raf_Model_Source_ActionType
{
    const SIGNUP_FLAT_RATE_DISCOUNT_LABEL             = 'Fixed flat rate discount for signup quantity';
    const SIGNUP_PERCENT_RATE_DISCOUNT_LABEL          = 'Fixed % discount for signup quantity';

    const ORDER_AMOUNT_FLAT_RATE_DISCOUNT_LABEL       = 'Fixed flat rate discount for all referrals purchase amount';
    const ORDER_AMOUNT_PERCENT_RATE_DISCOUNT_LABEL    = 'Fixed % discount for all referrals purchase amount';

    const ORDER_ITEM_QTY_FLAT_RATE_DISCOUNT_LABEL     = 'Fixed flat rate discount for quantity of the items purchased';
    const ORDER_ITEM_QTY_PERCENT_RATE_DISCOUNT_LABEL  = 'Fixed % discount for quantity of the items purchased';

    const FIXED_DISCOUNT_LABEL                        = 'Fixed Discount';
    const PERCENT_DISCOUNT_LABEL                      = 'Percent Discount';

    const FIXED_DISCOUNT_VALUE                        = 1;
    const PERCENT_DISCOUNT_VALUE                      = 2;

    const TRANSACTION_TRIGGER                         = 'transaction';
    const DISCOUNT_TRIGGER                            = 'discount';

    const TRIGGER_INSTANCE_PREFIX                     = 'awraf/rule_action_';

    public function toOptionArray($type = null)
    {
        $helper = Mage::helper('awraf');
        switch ($type) {
            case AW_Raf_Model_Source_RuleType::SIGNUP_VALUE :
                $options = array(
                    self::FIXED_DISCOUNT_VALUE   => $helper->__(self::SIGNUP_FLAT_RATE_DISCOUNT_LABEL),
                    self::PERCENT_DISCOUNT_VALUE => $helper->__(self::SIGNUP_PERCENT_RATE_DISCOUNT_LABEL)
                );
                break;
            case AW_Raf_Model_Source_RuleType::ORDER_AMOUNT_VALUE :
                $options = array(
                    self::FIXED_DISCOUNT_VALUE   => $helper->__(self::ORDER_AMOUNT_FLAT_RATE_DISCOUNT_LABEL),
                    self::PERCENT_DISCOUNT_VALUE => $helper->__(self::ORDER_AMOUNT_PERCENT_RATE_DISCOUNT_LABEL)
                );
                break;
            case AW_Raf_Model_Source_RuleType::ORDER_ITEM_QTY_VALUE :
                $options = array(
                    self::FIXED_DISCOUNT_VALUE   => $helper->__(self::ORDER_ITEM_QTY_FLAT_RATE_DISCOUNT_LABEL),
                    self::PERCENT_DISCOUNT_VALUE => $helper->__(self::ORDER_ITEM_QTY_PERCENT_RATE_DISCOUNT_LABEL)
                );
                break;
            default :
                $options = array(
                    self::FIXED_DISCOUNT_VALUE   => $helper->__(self::FIXED_DISCOUNT_LABEL),
                    self::PERCENT_DISCOUNT_VALUE => $helper->__(self::PERCENT_DISCOUNT_LABEL)
                );
        }

        return $options;
    }

    public function getActionInstanceByTypeValue($typeValue)
    {
        $instanceClassName = null;
        switch ($typeValue) {
            case self::FIXED_DISCOUNT_VALUE:
                $instanceClassName = self::TRIGGER_INSTANCE_PREFIX . self::TRANSACTION_TRIGGER;
                break;
            case self::PERCENT_DISCOUNT_VALUE:
                $instanceClassName = self::TRIGGER_INSTANCE_PREFIX . self::DISCOUNT_TRIGGER;
                break;
        }

        return $instanceClassName;
    }
}