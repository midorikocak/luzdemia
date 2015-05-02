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

class AW_Raf_Model_Activity extends Mage_Core_Model_Abstract
{
    const COOKIE_NAME = 'awraf_referrer';
    const COOKIE_REFERRAL = 'awraf_referral';    
    /* */
    const STATUS_GUEST = 0;    
    const STATUS_SIGNUP_NOT_CONFIRMED = 2;    
    const STATUS_SIGNUP_CONFIRMED = 1;
    const STATUS_DISABLED = 4;
    /* */
    const SIGNUP_NEW_VALID = 1;
    const SIGNUP_NEW_CONFIRM = 2;
    const SIGNUP_BACK_LINK = 3;   

    protected function _construct()
    {
        parent::_construct();
        $this->_init('awraf/activity');
    }
}