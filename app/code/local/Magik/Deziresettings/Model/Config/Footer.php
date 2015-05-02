<?php


class Magik_Deziresettings_Model_Config_Footer
{

    public function toOptionArray()
    {
        return array(
            array(
	            'value'=>'simple',
	            'label' => Mage::helper('deziresettings')->__('simple')),
            array(
	            'value'=>'informative',
	            'label' => Mage::helper('deziresettings')->__('informative')),
        );
    }

}
