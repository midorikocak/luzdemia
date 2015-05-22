<?php
class Magik_Socialbar_Model_Mysql4_Socialbar extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("socialbar/socialbar", "id");
    }
}