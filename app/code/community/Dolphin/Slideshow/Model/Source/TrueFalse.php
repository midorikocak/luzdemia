<?php

class Dolphin_Slideshow_Model_Source_TrueFalse
{
    public function toOptionArray()
    {			
        return array(
			array('value'=>'true', 'label'=>Mage::helper('adminhtml')->__('True')),
			array('value'=>'false', 'label'=>Mage::helper('adminhtml')->__('False')),
        );
    }
}
