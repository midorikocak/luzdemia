<?php

class Temando_Temando_Model_System_Config_Source_Rule_Action_Adjustment_Type 
    extends Temando_Temando_Model_System_Config_Source {
    
    const DO_NOT_USE = null;
    const MARKUP_FIXED	  = '1';
    const MARKUP_PERCENT  = '2';
    const SUBSIDY_FIXED	  = '3';
    const SUBSIDY_PERCENT = '4';
    const MINMAX	  = '5';
    const CAPPED	  = '6';
    
    
    protected function _setupOptions() {
	$this->_options = array(
	    self::DO_NOT_USE => Mage::helper('temando')->__(' -- '),
	    self::MARKUP_FIXED  => Mage::helper('temando')->__('Markup (fixed)'),
	    self::MARKUP_PERCENT  => Mage::helper('temando')->__('Markup (percentage)'),
	    self::SUBSIDY_FIXED => Mage::helper('temando')->__('Subsidy (fixed)'),
	    self::SUBSIDY_PERCENT => Mage::helper('temando')->__('Subsidy (percentage)'),
	    self::MINMAX => Mage::helper('temando')->__('Min/Max Range'),
	    self::CAPPED  => Mage::helper('temando')->__('Override')
	);
    }
    
}