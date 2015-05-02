<?php

class Temando_Temando_Model_Mysql4_Box extends Mage_Core_Model_Mysql4_Abstract
{
    
    public function _construct()
    {
        $this->_init('temando/box', 'id');
    }
    
}
