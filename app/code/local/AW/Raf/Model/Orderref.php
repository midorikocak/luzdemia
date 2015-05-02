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

class AW_Raf_Model_Orderref extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('awraf/orderref');
    }

    public function getTotalDiscount(Mage_Sales_Model_Order $order)
    {
        try {
            $_result = false;
            $this->loadByOrderIncrement($order->getIncrementId());

            if ($this->getId()) {
                $orderInfo = new Varien_Object(Zend_Json::decode($this->getOrderInfo()));
                $_result = $orderInfo->getAppliedDiscount() + $orderInfo->getAppliedAmount();
            }

        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $_result;
    }

    public function loadByOrderIncrement($incrementId)
    {
        return $this->load($incrementId, 'order_increment');
    }
}