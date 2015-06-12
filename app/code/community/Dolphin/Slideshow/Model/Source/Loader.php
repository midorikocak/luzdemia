<?php

class Dolphin_Slideshow_Model_Source_Loader
{
    public function toOptionArray()
    {
        return array(
			array('value'=>'pie', 'label'=>Mage::helper('adminhtml')->__('Pie')),
			array('value'=>'bar', 'label'=>Mage::helper('adminhtml')->__('Bar')),
			array('value'=>'none', 'label'=>Mage::helper('adminhtml')->__('None')),
			
        );
    }
}
