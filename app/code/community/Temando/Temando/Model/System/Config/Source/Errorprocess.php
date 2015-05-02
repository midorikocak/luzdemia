<?php

class Temando_Temando_Model_System_Config_Source_Errorprocess extends Temando_Temando_Model_System_Config_Source
{

    const VIEW  = 'view';
    const FLAT  = 'flat';

    protected function _setupOptions()
    {
        $this->_options = array(
            self::FLAT  => Mage::helper('temando')->__('Show flat rate'),
            self::VIEW  => Mage::helper('temando')->__('Show error message'),
        );
    }

}
