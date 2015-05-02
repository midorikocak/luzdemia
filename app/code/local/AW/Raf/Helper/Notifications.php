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

class AW_Raf_Helper_Notifications extends Mage_Core_Helper_Abstract
{
    public function send($messageObject)
    {    
        Mage::getSingleton('core/translate')->setTranslateInline(false);
        $_result = Mage::getModel('core/email_template')
            ->setDesignConfig(array('area' => 'frontend', 'store' => $messageObject->getStoreId()))
            ->sendTransactional(
                Mage::helper('awraf/config')->getNotificationTemplate($messageObject->getStoreId()),
                Mage::helper('awraf/config')->getSenderData($messageObject->getStoreId()),
                $messageObject->getEmailLaunch(),
                null,
                $messageObject->getParams(),
                $messageObject->getStoreId()
            )
        ;
        Mage::getSingleton('core/translate')->setTranslateInline(true);
        return $_result;
    }
}