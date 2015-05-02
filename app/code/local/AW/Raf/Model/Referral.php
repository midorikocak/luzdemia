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

class AW_Raf_Model_Referral extends Mage_Core_Model_Abstract
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        parent::_construct();
        $this->_init('awraf/referral');
    }

    public function getReferral(Varien_Object $data)
    {        
        return $this->getCollection()->getReferral($data)->getFirstItem();
    }

    public function create(Varien_Object $referral)
    {
        $this->setData($referral->getData());
        $gmt = Mage::getModel('core/date')->gmtDate();

        if (!$this->getId()) {
            $this->setCreatedAt($gmt);
        }

        $this
            ->setUpdatedAt($gmt)
            ->save()
        ;

        return $this;
    }

    public function loadByEmail($customerEmail)
    {
        $this->_getResource()->loadByEmail($this, $customerEmail);
        return $this;
    }
}