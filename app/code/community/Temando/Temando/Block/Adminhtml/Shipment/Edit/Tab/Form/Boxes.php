<?php

class Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Form_Boxes extends Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Abstract
{
    
    /**
     * Array of all available packaging types (default & custom)
     * 
     * @var array 
     */
    protected $_packagingTypes = null;   
    
    /**
     * Returns array of all available packaging types 
     */
    public function getPackagingTypes()
    {
	if(is_null($this->_packagingTypes)) {
	    $this->_packagingTypes = Mage::getModel('temando/system_config_source_shipment_packaging')->toOptionArray();
	}
	
	return $this->_packagingTypes;
    }
    
}
