<?php

class Temando_Temando_Model_System_Config_Source_Sales_Order_Status {

    
    public function toOptionArray($isMultiselect=false) {
	
	$statuses = Mage::getSingleton('sales/order_config')->getStatuses();

	$options = array();
	
	foreach ($statuses as $code => $label) {
	    $options[] = array(
		'value' => $code,
		'label' => $label
	    );
	}
	if(!$isMultiselect){
            array_unshift($options, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
        }
	return $options;
    }

}

