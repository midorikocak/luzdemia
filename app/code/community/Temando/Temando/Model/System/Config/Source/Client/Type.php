<?php

class Temando_Temando_Model_System_Config_Source_Client_Type extends Temando_Temando_Model_System_Config_Source
{
    
    const INDIVIDUAL = 'Individual';
    const COMPANY    = 'Company';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::INDIVIDUAL => Mage::helper('temando')->__('Individual'),
            self::COMPANY    => Mage::helper('temando')->__('Company'),
        );
    }
    
}
