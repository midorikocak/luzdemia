<?php
$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */

$installer->startSetup();
$installer->run("
    ALTER TABLE `{$installer->getTable('slideshow')}` ADD `sort_order` TINYINT( 6 ) NOT NULL DEFAULT '0' AFTER `content` 
");
$installer->endSetup();
