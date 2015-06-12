<?php

class Dolphin_Slideshow_Model_Mysql4_Slideshow extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('slideshow/slideshow', 'slideshow_id');
    }
}