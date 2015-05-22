<?php
class Magik_Socialbar_Model_Mysql4_Socialsites extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the sociable_id refers to the key field in your database table.
        $this->_init('socialbar/socialsites', 'id');
    }
}