<?php

class Temando_Temando_Model_System_Config_Source_Rule_Condition_Time 
    extends Temando_Temando_Model_System_Config_Source {
    
    const DO_NOT_USE = null;
    const BEFORE     = '1';
    const AFTER	     = '2';
    
    protected function _setupOptions() {
	$this->_options = array(
	    self::DO_NOT_USE    => Mage::helper('temando')->__(' -- '),
	    self::BEFORE  => Mage::helper('temando')->__('before'),
	    self::AFTER      => Mage::helper('temando')->__('after')
	);
    }
    
}