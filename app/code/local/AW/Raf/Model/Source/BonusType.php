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

class AW_Raf_Model_Source_BonusType
{
    const FIXED_LABEL   = 'Fixed discount';
    const PERCENT_LABEL = 'Percent discount';

    const BONUS_VALUE   = 0;

    public function toOptionArray()
    {
        return array(
            array(
                'value' => AW_Raf_Model_Source_ActionType::FIXED_DISCOUNT_VALUE,
                'label' => Mage::helper('awraf')->__(self::FIXED_LABEL)
            ),
            array(
                'value' => AW_Raf_Model_Source_ActionType::PERCENT_DISCOUNT_VALUE,
                'label' => Mage::helper('awraf')->__(self::PERCENT_LABEL)
            )
        );
    }
}