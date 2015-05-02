<?php

class Temando_Temando_Model_System_Config_Source_Readytime extends Temando_Temando_Model_System_Config_Source
{
    
    const AM = 'AM';
    const PM = 'PM';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::AM => Mage::helper('temando')->__('AM'),
            self::PM => Mage::helper('temando')->__('PM'),
        );
    }
    
}
