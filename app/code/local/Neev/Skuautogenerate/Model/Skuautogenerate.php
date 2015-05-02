<?php

class Neev_Skuautogenerate_Model_Skuautogenerate extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('skuautogenerate/skuautogenerate');
    }
}