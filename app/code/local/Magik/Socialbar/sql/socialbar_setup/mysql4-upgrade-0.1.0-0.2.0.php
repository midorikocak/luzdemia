<?php

$installer = $this;

$installer->startSetup();



$installer->run("
 
INSERT INTO {$this->getTable('magik_socialsites')} VALUES ('19','Pinterest','PinExt.png','http://pinterest.com/pin/create/link/?url=PERMALINK&amp;media=Productmedia&amp;description=DESCRIPTION'),('20','Google Plus','googleplus.png','https://plus.google.com/share?url=PERMALINK');
 
");



$installer->endSetup();  



