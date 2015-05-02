<?php

class ES_Newssubscribers_Model_System_Config_Coupon
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'alphanum', 'label'=>Mage::helper('adminhtml')->__('Alphanumeric')),
            array('value' => 'alpha', 'label'=>Mage::helper('adminhtml')->__('Alphabetical')),
            array('value' => 'num', 'label'=>Mage::helper('adminhtml')->__('Numeric')),
        );
    }
}