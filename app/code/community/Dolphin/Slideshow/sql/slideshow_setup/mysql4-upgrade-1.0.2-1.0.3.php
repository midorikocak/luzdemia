<?php
$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */

$installer->startSetup();
$installer->run("
	ALTER TABLE `{$installer->getTable('slideshow')}` ADD `stores` VARCHAR( 255 ) NOT NULL DEFAULT '0' AFTER `slideshow_id`
");
$installer->endSetup();