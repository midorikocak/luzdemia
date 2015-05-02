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


$installer = $this;

$installer->startSetup();

$installer->run("
    
  CREATE TABLE IF NOT EXISTS {$this->getTable('awraf/rule')} (
    `rule_id` int(10) unsigned NOT NULL auto_increment,
    `rule_name` varchar(255) default NULL,
    `target` decimal(12,2) NOT NULL,
    `limit` int(10) unsigned DEFAULT NULL, 
    `action` decimal(12,2) NOT NULL,
    `type` SMALLINT(5) unsigned NOT NULL,
    `action_type` SMALLINT(5) unsigned NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    `store_ids` varchar(255) default NULL,
    `priority`  int(10) unsigned NOT NULL,
    `stop_on_first` tinyint(1) unsigned NOT NULL,
    `created_at` DATETIME NOT NULL,    
    `active_from`  DATETIME NOT NULL,
      PRIMARY KEY (`rule_id`),
      KEY `status` (`status`),
      KEY `action_type` (`action_type`),
      KEY `type` (`type`),
      KEY `priority` (`priority`),
      KEY `stop_on_first` (`stop_on_first`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    CREATE TABLE IF NOT EXISTS {$this->getTable('awraf/activity')} (
        `activity_id` INT(10) unsigned NOT NULL auto_increment,
        `type` SMALLINT(5) unsigned NOT NULL,
        `rr_id`  INT(10) unsigned NOT NULL,
        `rl_id` INT(10) unsigned default NULL,
        `website_id` TINYINT(1) unsigned NOT NULL,
        `related_object`  INT(10) DEFAULT NULL,
        `amount` decimal(12,2) NOT NULL,
        `created_at` DATETIME NOT NULL,
      PRIMARY KEY (`activity_id`),
      KEY `type` (`type`),
      KEY `rr_id` (`rr_id`),
      KEY `rl_id` (`rl_id`),
      KEY `website_id` (`website_id`),
      KEY `created_at` (`created_at`),
      KEY `related_object` (`related_object`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    CREATE TABLE IF NOT EXISTS {$this->getTable('awraf/transaction')} (
        `transaction_id` INT(10) unsigned NOT NULL auto_increment,
        `rule_id`  INT(10) unsigned default NULL,
        `customer_id` int(10) unsigned NOT NULL,        
        `website_id` TINYINT(1) unsigned NOT NULL,
        `discount` decimal(12,2) NOT NULL,
        `trigger_id` INT(10) unsigned default NULL,
        `comment` TEXT DEFAULT NULL,
        `created_at` DATETIME NOT NULL, 
      PRIMARY KEY (`transaction_id`),
      KEY `rule_id` (`rule_id`),
      KEY `customer_id` (`customer_id`),  
      KEY `website_id` (`website_id`),
      KEY `trigger_id` (`trigger_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    CREATE TABLE IF NOT EXISTS {$this->getTable('awraf/trigger')} (        
        `item_id` INT(10) unsigned NOT NULL auto_increment,
        `customer_id`  INT(10) unsigned NOT NULL,
        `rule_id` INT(10) unsigned NOT NULL,
        `trig_qty` INT(10) unsigned NOT NULL,
        `rest_amount` decimal(12,2) NOT NULL,
        `created_at`  DATETIME NOT NULL,
      PRIMARY KEY (`item_id`),
      KEY `customer_id` (`customer_id`),
      KEY `rule_id` (`rule_id`),  
      KEY `trig_qty` (`trig_qty`)     
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
     CREATE TABLE IF NOT EXISTS {$this->getTable('awraf/discount')} (
        `item_id` INT(10) unsigned NOT NULL auto_increment,
        `customer_id` INT(10) unsigned NOT NULL,
        `rule_id` INT(10) unsigned DEFAULT NULL,
        `comment` TEXT DEFAULT NULL,
        `website_id` INT(10) unsigned NOT NULL,
        `discount` decimal(12,2) NOT NULL,        
        `created_at`  DATETIME NOT NULL,
        `trig_qty` INT(10) unsigned NOT NULL,
        `type` INT(10) unsigned DEFAULT 2,
        `trigger_id` INT(10) DEFAULT NULL,
      PRIMARY KEY (`item_id`),
      KEY `customer_id` (`customer_id`),
      KEY `type` (`type`),
      KEY `rule_id` (`rule_id`),
      KEY `trig_qty` (`trig_qty`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    

     CREATE TABLE IF NOT EXISTS {$this->getTable('awraf/referral')} (
       `referral_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
       `referrer_id` int(10) unsigned NOT NULL,
       `email` varchar(255) DEFAULT NULL,
       `customer_id` int(10) unsigned DEFAULT NULL,
       `website_id` smallint(5) unsigned NOT NULL,
       `store_id` smallint(5) unsigned NOT NULL,
       `status` smallint(5) unsigned NOT NULL,
       `created_at` datetime NOT NULL,     
       `updated_at` datetime NOT NULL,      
      PRIMARY KEY (`referral_id`),
      KEY `referrer_id` (`referrer_id`),
      KEY `customer_id` (`customer_id`),
      KEY `website_id` (`website_id`),
      KEY `store_id` (`store_id`)      
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;    

  CREATE TABLE IF NOT EXISTS {$this->getTable('awraf/order_to_ref')} (
       `item_id`  int(10) unsigned NOT NULL AUTO_INCREMENT,
       `order_increment` varchar(255) NOT NULL,
       `referral_id` int(10) unsigned DEFAULT NULL,
       `customer_id` int(10) unsigned DEFAULT NULL,
       `website_id` int(10) unsigned NOT NULL,
       `order_info` text DEFAULT NULL,     
       `created_at` datetime NOT NULL,    
      PRIMARY KEY (`item_id`),
      KEY `referral_id` (`referral_id`),  
      KEY `customer_id` (`customer_id`),
      KEY `website_id` (`website_id`)    
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    CREATE TABLE IF NOT EXISTS {$this->getTable('awraf/statistics')} (
        `item_id` INT(10) unsigned NOT NULL auto_increment,
        `customer_id` INT(10) unsigned NOT NULL,
        `website_id` INT(10) unsigned NOT NULL,
        `referrals_number` INT(10) unsigned default '0',
        `qty_purchased` INT(10) unsigned default '0',
        `amount_purchased`  decimal(12,2) default '0.00',
        `earned`   decimal(12,2) default '0.00',
        `spent`  decimal(12,2) default '0.00', 
        `stats_update` DATETIME NOT NULL, 
         PRIMARY KEY (`item_id`),         
         KEY `customer_id` (`customer_id`),
         KEY `website_id` (`website_id`),
         KEY `referrals_number` (`referrals_number`),  
         KEY `qty_purchased` (`qty_purchased`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
  
");

$installer->endSetup();

