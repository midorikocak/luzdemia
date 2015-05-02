<?php

class Temando_Temando_Model_Api_Request_Anywhere extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/api_request_anywhere');
    }
    
    public function toRequestArray()
    {
        if (!$this->validate()) {
            return false;
        }

        $data = array(
            'itemNature' => 'Domestic',
            'itemMethod' => 'Door to Door',
            'destinationCountry' => $this->getDestinationCountry(),
            'destinationCode' => $this->getDestinationPostcode(),
            'destinationSuburb' => $this->getDestinationCity(),
            'destinationIs' => 'Residence',
            'destinationBusNotifyBefore' => 'N',
            'destinationBusLimitedAccess' => 'N',
            'originBusNotifyBefore' => 'Y',
	    'originDescription' => Temando_Temando_Helper_Data::DEFAULT_WAREHOUSE_NAME
        );

        if (Mage::helper('temando')->isStreetWithPO($this->getDestinationStreet())) {
            $data['destinationResPostalBox'] = 'Y';
        }

        return $data;
    }
    
    public function validate()
    {
        return
            $this->getDestinationCountry() &&
            $this->getDestinationPostcode() &&
            $this->getDestinationCity();
    }
    
}
