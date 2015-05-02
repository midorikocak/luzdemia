<?php

class Temando_Temando_Model_System_Config_Backend_Form_Field_Required_Businessresidential extends Mage_Core_Model_Config_Data
{
    
    protected function _beforeSave()
    {
        $value = $this->getValue();
        $types = array('Business', 'Residence');
        
        if (!in_array($value, $types)) {
            Mage::throwException(Mage::helper('temando')->__('Please select a location type from the list.', $value));
        }
        return $this;
    }
    
}
