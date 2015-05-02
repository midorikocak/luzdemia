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

class AW_Raf_Block_Sales_Order_Invoice_Totals extends Mage_Core_Block_Template
{
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $customer = Mage::getModel('customer/customer')->load($parent->getSource()->getOrder()->getCustomerId());
        if (!$customer->getId()) {
            return;
        }

        if ((float)$parent->getSource()->getAwrafs()) {
            $parent->addTotal(
                new Varien_Object(
                    array(
                         'code'       => 'awraf',
                         'value'      => $parent->getSource()->getAwrafs(),
                         'base_value' => $parent->getSource()->getAwrafsBase(),
                         'label'      => Mage::helper('awraf')->__('Applied Discount For Referred Friends')
                    )
                ),
                'subtotal'
            );
        }

        return $this;
    }
}