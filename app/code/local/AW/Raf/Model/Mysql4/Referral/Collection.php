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

class AW_Raf_Model_Mysql4_Referral_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{    
    public function _construct()
    {
        parent::_construct();
        $this->_init('awraf/referral');
    }

    public function getReferral(Varien_Object $data)
    {
        foreach ($data->toArray() as $key => $val) {
            $val = (array) $val;
            $this
                ->getSelect()
                ->where("main_table.{$key} IN(?)", $val)
            ;
        }

        return $this;
    }

    public function getSelectCountSql() {
        $_select = parent::getSelectCountSql();
        $_select
            ->reset(Zend_Db_Select::GROUP)
            ->reset(Zend_Db_Select::FROM)
            ->from(array('main_table' => $this->_mainTable), '')
        ;
        return $_select;
    }

    public function setReferrerFilter($referrerId)
    {
        return $this->addFieldToFilter('main_table.referrer_id', (array)$referrerId);
    }

    public function joinRafActivityTable()
    {
         $this
             ->getSelect()
             ->joinLeft(
                 array(
                      'activity' => $this->getTable('awraf/activity')
                 ),
                 'main_table.referral_id = activity.rl_id'
             )
         ;

        return $this;
    }

    public function groupBy($columnName)
    {
        $this
            ->getSelect()
            ->group($columnName)
        ;

        return $this;
    }
}