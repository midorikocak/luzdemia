<?php

class Dolphin_Slideshow_Model_Source_PiePosition
{
    public function toOptionArray()
    {			
        return array(
			array('value'=>'rightTop', 'label'=>Mage::helper('adminhtml')->__('RightTop')),
			array('value'=>'leftTop', 'label'=>Mage::helper('adminhtml')->__('LeftTop')),
			array('value'=>'leftBottom', 'label'=>Mage::helper('adminhtml')->__('LeftBottom')),
			array('value'=>'rightBottom', 'label'=>Mage::helper('adminhtml')->__('RightBottom')),
        );
    }
}
