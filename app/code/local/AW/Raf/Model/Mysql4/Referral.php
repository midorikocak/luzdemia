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

class AW_Raf_Model_Mysql4_Referral extends Mage_Core_Model_Mysql4_Abstract
{    
    protected function _construct()
    {
        $this->_init('awraf/referral', 'referral_id');
    }

    public function loadByEmail(AW_Raf_Model_Referral $refferal, $email)
    {
        $adapter = $this->_getReadAdapter();
        $bind    = array('email' => $email);
        $select  = $adapter->select()
            ->from($this->getTable('awraf/referral'))
            ->where('email = :email')
        ;

        if (Mage::getStoreConfig('customer/account_share/scope')) {
            if (!$refferal->hasData('website_id')) {
                Mage::throwException(
                    Mage::helper('awraf')->__('Refferal website ID must be specified when using the website scope')
                );
            }
            $bind['website_id'] = (int)$refferal->getWebsiteId();
            $select->where('website_id = :website_id');
        }

        $refferalId = $adapter->fetchOne($select, $bind);
        if ($refferalId) {
            $this->load($refferal, $refferalId);
        } else {
            $refferal->setData(array());
        }

        return $this;
    }
}