<?php

class ES_Newssubscribers_Model_System_Config_Template
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'default', 'label'=>Mage::helper('adminhtml')->__('Default')),
            array('value' => 'label', 'label'=>Mage::helper('adminhtml')->__('Label')),
        );
    }
}