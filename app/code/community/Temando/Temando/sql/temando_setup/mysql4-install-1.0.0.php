<?php

set_time_limit(0);

/* @var $this Mage_Eav_Model_Entity_Setup */
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer = $this;
$installer->startSetup();

// Add custom Temando attributes
$installer->addAttributeGroup('catalog_product', 'Default', 'Temando', 90);
$installer->addAttribute('catalog_product', 'temando_packaging_mode',
        array(
            'type' => 'int',
            'label' => 'Packaging Mode',
            'group' => 'Temando',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'source' => 'temando/entity_attribute_source_packaging_mode',
            'input' => 'select',
            'default' => false,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'searchable' => true,
            'filterable' => true,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false
        )
);

$installer->addAttribute('catalog_product', 'temando_packaging',
        array(
            'type' => 'varchar',
            'label' => 'Packaging',
            'group' => 'Temando',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'source' => 'temando/entity_attribute_source_packaging',
            'input' => 'select',
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false
        )
);

$installer->addAttribute('catalog_product', 'temando_fragile',
        array(
            'type' => 'int',
            'label' => 'Fragile',
            'group' => 'Temando',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'source' => 'eav/entity_attribute_source_boolean',
            'input' => 'select',
            'default' => false,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false
        )
);

$installer->addAttribute('catalog_product', 'temando_length',
        array(
            'type' => 'decimal',
            'label' => 'Length',
            'group' => 'Temando',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false
        )
);

$installer->addAttribute('catalog_product', 'temando_width',
        array(
            'type' => 'decimal',
            'label' => 'Width',
            'group' => 'Temando',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false
        )
);

$installer->addAttribute('catalog_product', 'temando_height',
        array(
            'type' => 'decimal',
            'label' => 'Height',
            'group' => 'Temando',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false
        )
);

// Create custom tables
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('temando_carrier')};
CREATE TABLE {$this->getTable('temando_carrier')} (
  `id` int(13) UNSIGNED NOT NULL AUTO_INCREMENT,
  `carrier_id` bigint(20) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `company_contact` text NOT NULL,
  `street_address` text NOT NULL,
  `street_suburb` varchar(255) NOT NULL,
  `street_city` varchar(255) NOT NULL,
  `street_state` varchar(255) NOT NULL,
  `street_postcode` varchar(255) NOT NULL,
  `street_country` varchar(255) NOT NULL,
  `postal_address` text NOT NULL,
  `postal_suburb` varchar(255) NOT NULL,
  `postal_city` varchar(255) NOT NULL,
  `postal_state` varchar(255) NOT NULL,
  `postal_postcode` varchar(255) NOT NULL,
  `postal_country` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('temando_carrier')} (`id`, `carrier_id`, `company_name`) VALUES
(1, 54381, 'Allied Express'),
(2, 54426, 'Allied Express (Bulk)'),
(3, 54359, 'Startrack'),
(4, 54396, 'Startrack - Auth To Leave'),
(5, 54360, 'Bluestar Logistics'),
(6, 54429, 'Bluestar Logistics Bulk'),
(7, 54433, 'Capital Transport Courier'),
(8, 54432, 'Capital Transport HDS'),
(9, 54425, 'Couriers Please'),
(10, 54343, 'DHL'),
(11, 54430, 'DHL MultiZone'),
(12, 54431, 'DHL SingleZone'),
(13, 54427, 'Fastway Couriers Adhoc'),
(14, 54428, 'Fastway Couriers Bulk'),
(15, 54344, 'Hunter Express'),
(16, 54398, 'Hunter Express (bulk)'),
(17, 54358, 'Mainfreight'),
(18, 54410, 'Northline');
");

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('temando_quote')};
CREATE TABLE {$this->getTable('temando_quote')} (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `magento_quote_id` int(10) UNSIGNED NOT NULL,
  `carrier_id` int(13) UNSIGNED NOT NULL,
  `accepted` boolean NOT NULL DEFAULT '0',
  `total_price` decimal(12, 4) NOT NULL,
  `base_price` decimal(12, 4) NOT NULL,
  `tax` decimal(12, 4) NOT NULL,
  `insurance_total_price` decimal(12, 4) NOT NULL,
  `carbon_total_price` decimal(12, 4) NOT NULL,
  `footprints_total_price` decimal(12, 4) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `delivery_method` text NOT NULL,
  `eta_from` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eta_to` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `guaranteed_eta` boolean NOT NULL DEFAULT '0',
  `extras` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX (`carrier_id`),
    CONSTRAINT `fk_carrier_id`
      FOREIGN KEY (`carrier_id`)
      REFERENCES {$this->getTable('temando_carrier')} (`id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('temando_shipment')};
CREATE TABLE {$this->getTable('temando_shipment')} (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(10) UNSIGNED NOT NULL,
  `customer_selected_quote_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `customer_selected_options` TEXT NOT NULL,
  `customer_selected_quote_description` TEXT NOT NULL,
  `admin_selected_quote_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `anticipated_cost` decimal(12,4) UNSIGNED NOT NULL,
  `status` int(10) NOT NULL DEFAULT '0',
  `booking_request_id` int(13) UNSIGNED NOT NULL,
  `booking_number` varchar(255) NOT NULL,
  `consignment_number` varchar(255) NOT NULL,
  `consignment_document` longtext NOT NULL,
  `consignment_document_type` varchar(32) NOT NULL,
  `label_document` longtext not null,
  `label_document_type`	varchar(32) NOT NULL,
  `destination_contact_name` varchar(255) NOT NULL,
  `destination_company_name` varchar(255) NOT NULL,
  `destination_street` varchar(255) NOT NULL,
  `destination_city` varchar(255) NOT NULL,
  `destination_postcode` varchar(255) NOT NULL,
  `destination_region` varchar(255) NOT NULL,
  `destination_country` varchar(255) NOT NULL,
  `destination_phone` varchar(255) NOT NULL,
  `destination_email` varchar(255) NOT NULL,
  `ready_date` DATE NULL DEFAULT NULL,
  `ready_time` VARCHAR(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


$installer->run("
DROP TABLE IF EXISTS {$this->getTable('temando_box')};
CREATE TABLE {$this->getTable('temando_box')} (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` int(13) NOT NULL,
  `comment` text NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '1',
  `value` decimal(12,4) NOT NULL,
  `length` decimal(12,4) NOT NULL,
  `width` decimal(12,4) NOT NULL,
  `height` decimal(12,4) NOT NULL,
  `measure_unit` varchar(255) NOT NULL,
  `weight` decimal(12,4) NOT NULL,
  `weight_unit` varchar(255) NOT NULL,
  `fragile` tinyint(1) NOT NULL DEFAULT '0',
  `packaging` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shipment_id` (`shipment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
");

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('temando_manifest')};
CREATE TABLE {$this->getTable('temando_manifest')} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` varchar(255) NOT NULL DEFAULT '',
  `carrier_id` int(10) NOT NULL DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `manifest_document_type` varchar(250) NOT NULL DEFAULT '',
  `manifest_document` longtext NOT NULL,
  `label_document_type` varchar(250) NOT NULL DEFAULT '',
  `label_document` longtext NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Awaiting Confirmation',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
;");


// Insert a list of states into the regions database. Magento will then pick
// these up when displaying addresses and allow the user to select from a drop-down
// list, rather than having to type them in manually.
$regions = array(
    array('code' => 'ACT', 'name' => 'Australia Capital Territory'),
    array('code' => 'NSW', 'name' => 'New South Wales'),
    array('code' => 'NT', 'name' => 'Northern Territory'),
    array('code' => 'QLD', 'name' => 'Queensland'),
    array('code' => 'SA', 'name' => 'South Australia'),
    array('code' => 'TAS', 'name' => 'Tasmania'),
    array('code' => 'VIC', 'name' => 'Victoria'),
    array('code' => 'WA', 'name' => 'Western Australia')
);

$db = Mage::getSingleton('core/resource')->getConnection('core_read');

foreach ($regions as $region) {
    // Check if this region has already been added
    $result = $db->fetchOne("SELECT code FROM " . $this->getTable('directory_country_region') . " WHERE `country_id` = 'AU' AND `code` = '" . $region['code'] . "'");
    if ($result != $region['code']) {
        $installer->run(
                "INSERT INTO `{$this->getTable('directory_country_region')}` (`country_id`, `code`, `default_name`) VALUES
            ('AU', '" . $region['code'] . "', '" . $region['name'] . "');
            INSERT INTO `{$this->getTable('directory_country_region_name')}` (`locale`, `region_id`, `name`) VALUES
            ('en_US', LAST_INSERT_ID(), '" . $region['name'] . "'), ('en_AU', LAST_INSERT_ID(), '" . $region['name'] . "');"
        );
    }
}

$installer->endSetup();
