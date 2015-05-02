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

class AW_Raf_Model_System_Config_LimitValidator extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        if ($this->isValueChanged()) {
            $value = $this->getValue();
            if ($value != '' && ((int)$value <= 0 || (int)$value > 100)) {
                $message = Mage::helper('awraf')->__(
                    'The %s you entered is invalid.'
                    . ' Please make sure that it greater than 0 and less than or equal to 100',
                    $this->getFieldConfig()->label
                );
                Mage::throwException($message);
            }
        }

        return $this;
    }
}