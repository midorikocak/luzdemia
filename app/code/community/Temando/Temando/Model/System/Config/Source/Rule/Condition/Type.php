<?php

class Temando_Temando_Model_System_Config_Source_Rule_Condition_Type 
    extends Temando_Temando_Model_System_Config_Source {
    
    const WEIGHT    = '1';
    const SUBTOTAL  = '2';
    const ZONE      = '3';
    
    protected function _setupOptions() {
	$this->_options = array(
	    self::WEIGHT    => Mage::helper('temando')->__('Weight'),
	    self::SUBTOTAL  => Mage::helper('temando')->__('Subtotal'),
	    self::ZONE      => Mage::helper('temando')->__('Zone')
	);
    }
    
}
