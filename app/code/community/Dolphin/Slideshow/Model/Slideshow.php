<?php

class Dolphin_Slideshow_Model_Slideshow extends Mage_Core_Model_Abstract
{
	//DynamicFileUrl = 'slideshow' . DS . 'slides';
    public function _construct()
    {
        parent::_construct();
        $this->_init('slideshow/slideshow');
    }
}