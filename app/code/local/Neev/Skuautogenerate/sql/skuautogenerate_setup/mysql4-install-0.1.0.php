<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('skuautogenerate')};
CREATE TABLE {$this->getTable('skuautogenerate')} (
  `skuautogenerate_id` int(11) unsigned NOT NULL auto_increment,
    `appendstring` varchar(5) NOT NULL default '',
  `prependstring` varchar(5) NOT NULL default '',
  `stringfunction` varchar(50) NOT NULL default '',
  `min_length` varchar(5) NOT NULL default '',
  `product_type` varchar(50) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`skuautogenerate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
$installer->run("INSERT INTO {$this->getTable('skuautogenerate')} (
`skuautogenerate_id` ,
`appendstring` ,
`prependstring` ,
`stringfunction` ,
`min_length` ,
`product_type` ,
`status` 
)
VALUES (
NULL , '', 'PRE', 'prepend', '10', 'unique', '1'
)");

$installer->endSetup(); 