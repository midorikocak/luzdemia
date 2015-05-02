<?php
/**
 * Toogas Lda.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA (End-User License Agreement)
 * that is bundled with this package in the file toogas_license-free.txt.
 * It is also available at this URL:
 * http://www.toogas.com/licences/toogas_license-free.txt
 *
 * @category   Toogas
 * @package    Toogas_Featuredpopup
 * @copyright  Copyright (c) 2011 Toogas Lda. (http://www.toogas.com)
 * @license    http://www.toogas.com/licences/toogas_license-free.txt
 */
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('toogas_featuredpopup')};
CREATE TABLE {$this->getTable('toogas_featuredpopup')} (
  `featuredpopup_id` int(11) unsigned NOT NULL auto_increment,
  `popup_name` varchar(30) NOT NULL default '',
  `image_link` varchar(255) NOT NULL default '',
  `width_image` smallint(4) NOT NULL default '0',
  `height_image` smallint(4) NOT NULL default '0',    
  `url_link` varchar(255) NOT NULL default '',  
  `is_active` tinyint(1) NOT NULL default '1',
  `from_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `to_date` datetime NOT NULL default '0000-00-00 00:00:00',    
  `css_style` varchar(64) NOT NULL default '',
  `js_style` varchar(64) NOT NULL default '',
  `delay_start` tinyint(3) NOT NULL default '0',
  `delay_close` tinyint(3) NOT NULL default '0',
  `priority` tinyint(4) NOT NULL default '0',      
  `opacity` decimal(3,2) NOT NULL default '0.4',     
  PRIMARY KEY (`featuredpopup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$this->getTable('toogas_featuredpopup_store')}`;
CREATE TABLE `{$this->getTable('toogas_featuredpopup_store')}` (
  `featured_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`featured_id`,`store_id`),
  CONSTRAINT `FK_FEATUREDPOPUP_STORE_FEATUREDPOPUP` FOREIGN KEY (`featured_id`) REFERENCES `{$this->getTable('toogas_featuredpopup')}` (`featuredpopup_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_FEATUREDPOPUP_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store for featured popup';


    ");
    

$installer->endSetup(); 