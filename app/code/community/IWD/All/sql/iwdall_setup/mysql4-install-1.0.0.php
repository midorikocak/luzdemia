<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
		drop table if exists {$this->getTable('iwd_notification')};
		
		CREATE TABLE {$this->getTable('iwd_notification')} (
		 `entity_id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` text,
		  `description` text NOT NULL,
		  `severity` int(2) DEFAULT NULL,
		  `url` text,
		  `date_added` datetime NOT NULL,
		  `view` int(1) NOT NULL DEFAULT '0',
		  `out_id` int(11) DEFAULT NULL,
		  PRIMARY KEY (`entity_id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
");

$installer->endSetup();