<?php

class Temando_Temando_Model_System_Config_Source_Unit_Weight extends Temando_Temando_Model_System_Config_Source_Unit
{
    
    const GRAMS     = 'Grams';
    const KILOGRAMS = 'Kilograms';
    const OUNCES    = 'Ounces';
    const POUNDS    = 'Pounds';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::GRAMS     => 'Grams',
            self::KILOGRAMS => 'Kilograms',
            self::OUNCES    => 'Ounces',
            self::POUNDS    => 'Pounds',
        );
    }
    
    protected function _setupBriefOptions()
    {
        $this->_brief_options = array(
            self::GRAMS     => 'g',
            self::KILOGRAMS => 'kg',
            self::OUNCES    => 'oz.',
            self::POUNDS    => 'lb.',
        );
    }
    
}
