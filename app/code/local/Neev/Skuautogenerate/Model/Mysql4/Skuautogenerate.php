<?php

class Neev_Skuautogenerate_Model_Mysql4_Skuautogenerate extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the skuautogenerate_id refers to the key field in your database table.
        $this->_init('skuautogenerate/skuautogenerate', 'skuautogenerate_id');
    }
}