<?php

class Temando_Temando_Model_System_Config_Source_Country extends Temando_Temando_Model_System_Config_Source
{
    
    protected $_options;
    
    public function _setupOptions() {
	
	if(!$this->_options) {
	    $countries = Mage::getResourceModel('directory/country_collection')->loadData()->toOptionArray(false);
	    
	    foreach($countries as $country => $arr) {
		$this->_options[$arr['value']] = $arr['label']; 
	    }
	}
    }
    
}