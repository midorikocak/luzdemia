<?php

class Temando_Temando_Model_System_Config_Source_Rule_Type 
    extends Temando_Temando_Model_System_Config_Source {
    
    const FLATRATE = '1';
    const FREE	   = '2';
    const DYNAMIC  = '3';
    const RESTRICT = '4';
    
    protected function _setupOptions() {
	$this->_options = array(
	    self::FLATRATE => Mage::helper('temando')->__('Flat Rate'),
	    self::FREE	   => Mage::helper('temando')->__('Free Shipping'),
	    self::DYNAMIC  => Mage::helper('temando')->__('Dynamic'),
	    self::RESTRICT => Mage::helper('temando')->__('Restrict'),
	);
    }
    
}