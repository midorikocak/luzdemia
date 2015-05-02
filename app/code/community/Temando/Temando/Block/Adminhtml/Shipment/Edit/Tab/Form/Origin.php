<?php

class Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Form_Origin extends Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Abstract
{
    
    public function getWarehouse() {
        $origin = new Varien_Object(array(
            'name' => Temando_Temando_Helper_Data::DEFAULT_WAREHOUSE_NAME,
            'street' => Mage::getStoreConfig('temando/origin/street'),
            'postcode' => Mage::getStoreConfig('temando/origin/postcode'),
            'city' => Mage::getStoreConfig('temando/origin/city'),
            'country' => Mage::getStoreConfig('temando/origin/country'),
            'type' => Mage::getStoreConfig('temando/origin/type')
        ));
        
        return $origin;
        
    }
    
}
