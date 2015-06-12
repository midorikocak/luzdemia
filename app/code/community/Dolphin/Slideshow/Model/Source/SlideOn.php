<?php

class Dolphin_Slideshow_Model_Source_SlideOn
{
    public function toOptionArray()
    {			
        return array(
			array('value'=>'random', 'label'=>Mage::helper('adminhtml')->__('Random')),
			array('value'=>'next', 'label'=>Mage::helper('adminhtml')->__('Next')),
			array('value'=>'prev', 'label'=>Mage::helper('adminhtml')->__('Prev')),
        );
    }
}
