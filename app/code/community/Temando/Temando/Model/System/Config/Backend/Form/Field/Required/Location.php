<?php

class Temando_Temando_Model_System_Config_Backend_Form_Field_Required_Location extends Temando_Temando_Model_System_Config_Backend_Form_Field_Required_Text
{

    /**
     * Processing object after save data
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterSave()
    {
        try {
            $api = Mage::getModel('temando/api_client');
            $api->connect(
                Mage::helper('temando')->getConfigData('general/username'),
                Mage::helper('temando')->getConfigData('general/password'),
                Mage::helper('temando')->getConfigData('general/sandbox')
            );
	    
	    //try to update 'Magento Warehouse'
	    $magentoWarehouse = Mage::helper('temando')->getOriginRequestArray(new Varien_Object($this->getFieldsetData()));	    
	    try {
		$api->updateLocation(array('location' => $magentoWarehouse));
	    } catch (Exception $e) {
		try {
		    //if error updating location, location probably does not exist - try to create
		    Mage::log($e->getMessage());
		    $api->createLocation(array('location' => $magentoWarehouse));
		} catch (Exception $e) {
		    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('temando')->__('An error occured when synchronizing origin location data with temando.com. Please resave your configuration.'));
		    Mage::log($e->getMessage());
		}	
	    } 
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }

        return parent::_afterSave();
    }
    
}
