<?php


class Magik_Deziresettings_Model_Config_Width
{

    public function toOptionArray()
    {
        return array(
            array(
	            'value' => 'flexible',
	            'label' => Mage::helper('deziresettings')->__('flexible')),
            array(
	            'value' => 'fixed',
	            'label' => Mage::helper('deziresettings')->__('fixed')),
        );
    }

}
