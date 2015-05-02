<?php

class Temando_Temando_Model_System_Config_Source_Regions extends Temando_Temando_Model_System_Config_Source
{
    
    protected function _setupOptions()
    {
        $this->_options = array();
        
        $regions = Mage::getModel('directory/region')->getCollection();
        /* @var $regions Mage_Directory_Model_Mysql4_Region_Collection */
//        $option_array = $regions
//            ->addCountryFilter('AU');
        
        $this->_options = array();
        
        foreach ($regions as $region) {
            /* @var $region Mage_Directory_Model_Region */
            $this->_options[strtoupper($region->getCode())] = $region->getName();
        }
    }
    
}
