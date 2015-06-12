<?php

class Dolphin_Slideshow_Model_Source_BarDirection
{
    public function toOptionArray()
    {			
        return array(
			array('value'=>'leftToRight', 'label'=>Mage::helper('adminhtml')->__('LeftToRight')),
			array('value'=>'rightToLeft', 'label'=>Mage::helper('adminhtml')->__('RightToLeft')),
			array('value'=>'topToBottom', 'label'=>Mage::helper('adminhtml')->__('TopToBottom')),
			array('value'=>'bottomToTop', 'label'=>Mage::helper('adminhtml')->__('BottomToTop')),
			
        );
    }
}
