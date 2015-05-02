<?php

class Temando_Temando_Model_System_Config_Source_Unit_Measure extends Temando_Temando_Model_System_Config_Source_Unit
{
    
    const CENTIMETRES = 'Centimetres';
    const METRES      = 'Metres';
    const INCHES      = 'Inches';
    const FEET        = 'Feet';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::CENTIMETRES => 'Centimetres',
        	self::METRES      => 'Metres',
            self::INCHES      => 'Inches',
            self::FEET        => 'Feet',
        );
    }
    
    protected function _setupBriefOptions()
    {
        $this->_brief_options = array(
            self::CENTIMETRES => 'cm',
        	self::METRES      => 'm',
            self::INCHES      => 'in.',
            self::FEET        => 'ft.',
        );
    }
    
}
