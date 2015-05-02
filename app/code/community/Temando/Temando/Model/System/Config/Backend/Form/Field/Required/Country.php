<?php

class Temando_Temando_Model_System_Config_Backend_Form_Field_Required_Country extends Mage_Core_Model_Config_Data
{
    
    protected function _beforeSave()
    {
        $value = $this->getValue();
        $collection = Mage::getModel('directory/country')->getCollection();
        if (is_callable($collection, "getAllIds")) {
            $countries = $collection->getAllIds();

            if (!in_array($value, $countries)) {
                Mage::throwException(Mage::helper('temando')->__('Please select a country from the list.', $value));
            }
        } else {
            $countries = $collection->toOptionArray();
            $found = false;
            foreach ($countries as $c) {
                if ($c['value'] == $value) {
                    $found = true;
                }
            }
            if (!$found) {
                Mage::throwException(Mage::helper('temando')->__('Please select a country from the list.', $value));
            }
        }

        return $this;
    }
    
}
