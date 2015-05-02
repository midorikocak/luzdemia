<?php

class Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Form_Status extends Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Abstract
{
    /**
     * @var Temando_Temando_Model_Quote
     */
    protected $_customer_selected_quote = null;
    
	/**
     * Gets the description of the Temando quote selected by the customer.
     *
     * @return Temando_Temando_Model_Quote
     */
    public function getCustomerSelectedQuoteDescription()
    {
        return $this->getShipment()->getCustomerSelectedQuoteDescription();
    }
    
    public function getShipmentStatusText()
    {
        return Mage::getModel('temando/system_config_source_shipment_status')
            ->getOptionLabel($this->getShipment()->getStatus());
    }
    
}
