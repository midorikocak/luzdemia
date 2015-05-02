<?php


class Magik_Deziresettings_Model_Config_Position
{

    public function toOptionArray()
    {
        return array(
            array(
	            'value'=>'top-left',
	            'label' => Mage::helper('deziresettings')->__('Top Left')),
            array(
	            'value'=>'top-right',
	            'label' => Mage::helper('deziresettings')->__('Top Right')),                       

        );
    }

}
