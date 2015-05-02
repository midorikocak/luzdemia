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


/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

if ($installer->tableExists($this->getTable('awraf/rule'))) {
    return;
}

$tablePrefix = (string)Mage::getConfig()->getTablePrefix();
$rafTablesName = array(
    'awraf/rule'            => $tablePrefix . 'aw_raf_rule',
    'awraf/activity'        => $tablePrefix . 'aw_raf_activity',
    'awraf/transaction'     => $tablePrefix . 'aw_raf_transaction',
    'awraf/discount'        => $tablePrefix . 'aw_raf_discount',
    'awraf/referral'        => $tablePrefix . 'aw_raf_referral',
    'awraf/trigger'         => $tablePrefix . 'aw_raf_trigger',
    'awraf/order_to_ref'    => $tablePrefix . 'aw_raf_orderref',
    'awraf/statistics'      => $tablePrefix . 'aw_raf_statistics',

);

$installer->startSetup();
$installer->run("
RENAME TABLE {$rafTablesName['awraf/rule']} TO {$this->getTable('awraf/rule')};
RENAME TABLE {$rafTablesName['awraf/activity']} TO {$this->getTable('awraf/activity')};
RENAME TABLE {$rafTablesName['awraf/transaction']} TO {$this->getTable('awraf/transaction')};
RENAME TABLE {$rafTablesName['awraf/discount']} TO {$this->getTable('awraf/discount')};
RENAME TABLE {$rafTablesName['awraf/referral']} TO {$this->getTable('awraf/referral')};
RENAME TABLE {$rafTablesName['awraf/trigger']} TO {$this->getTable('awraf/trigger')};
RENAME TABLE {$rafTablesName['awraf/order_to_ref']} TO {$this->getTable('awraf/order_to_ref')};
RENAME TABLE {$rafTablesName['awraf/statistics']} TO {$this->getTable('awraf/statistics')};
");
$installer->endSetup();