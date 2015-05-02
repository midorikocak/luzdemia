<?php

class Temando_Temando_Model_System_Config_Backend_Form_Field_Required_Text extends Mage_Core_Model_Config_Data
{
    
    protected function _beforeSave()
    {
        $value = $this->getValue();
        $config = $this->getFieldConfig();
        
        if (!Zend_Validate::is($value, 'NotEmpty')) {
            Mage::throwException(Mage::helper('temando')->__('"' . $config->label . '" is a required field.', $value));
        }
        return $this;
    }
    
}
