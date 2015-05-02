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

class AW_Raf_Model_Source_RuleType
{
    const SIGNUP_LABEL              = 'Store sign up';
    const ORDER_AMOUNT_LABEL        = 'Discount for referrals purchase amount';
    const ORDER_ITEM_QTY_LABEL      = 'Discount for a number of items';

    const SIGNUP_VALUE              = 1;
    const ORDER_AMOUNT_VALUE        = 2;
    const ORDER_ITEM_QTY_VALUE      = 3;
    const SPENT_DISCOUNT            = 4;

    const SIGNUP_INSTANCE           = 'signup';
    const ORDER_AMOUNT_INSTANCE     = 'amount';
    const ORDER_ITEM_QTY_INSTANCE   = 'qty';

    const VALIDATOR_INSTANCE_PREFIX = 'awraf/rule_validator_';

    public function toOptionArray($typeValue = null)
    {
        $options = array(
            self::SIGNUP_VALUE         => self::SIGNUP_LABEL,
            self::ORDER_AMOUNT_VALUE   => self::ORDER_AMOUNT_LABEL,
            self::ORDER_ITEM_QTY_VALUE => self::ORDER_ITEM_QTY_LABEL
        );

        if (null !== $typeValue) {
            if (array_key_exists($typeValue, $options)) {
                $options = $options[$typeValue];
            }
        }

        return $options;
    }

    public function getAutoMessage($typeValue = null)
    {        
        $messages = array(
            AW_Raf_Model_Source_BonusType::BONUS_VALUE =>
                Mage::helper('awraf')->__('Auto message: Bonus for registration'),
            self::SIGNUP_VALUE                         =>
                Mage::helper('awraf')->__('Auto message: Bonus for referral signups'),
            self::ORDER_ITEM_QTY_VALUE                 =>
                Mage::helper('awraf')->__('Auto message: Bonus for the number of items purchased by referrals'),
            self::ORDER_AMOUNT_VALUE                   =>
                Mage::helper('awraf')->__('Auto message: Bonus for the amount of money spent by referrals'),
            self::SPENT_DISCOUNT                       =>
                Mage::helper('awraf')->__('Auto message: Bonus for referred friends spent on order')
        );

        if (null !== $typeValue) {
            if (array_key_exists($typeValue, $messages)) {
                $messages = $messages[$typeValue];
            }
        }

        return $messages;
    }

    /**
     * @return array
     */
    public function getValidatorTypes()
    {
        return array(
            self::SIGNUP_VALUE         => self::SIGNUP_INSTANCE,
            self::ORDER_AMOUNT_VALUE   => self::ORDER_AMOUNT_INSTANCE,
            self::ORDER_ITEM_QTY_VALUE => self::ORDER_ITEM_QTY_INSTANCE
        );
    }

    public function getValidatorInstanceByTypeValue($typeValue)
    {
        $instanceClassName = null;
        switch ($typeValue) {
            case self::SIGNUP_VALUE:
                $instanceClassName = self::VALIDATOR_INSTANCE_PREFIX . self::SIGNUP_INSTANCE;
                break;
            case self::ORDER_AMOUNT_VALUE:
                $instanceClassName = self::VALIDATOR_INSTANCE_PREFIX . self::ORDER_AMOUNT_INSTANCE;
                break;
            case self::ORDER_ITEM_QTY_VALUE:
                $instanceClassName = self::VALIDATOR_INSTANCE_PREFIX . self::ORDER_ITEM_QTY_INSTANCE;
                break;
        }

        return $instanceClassName;
    }
}