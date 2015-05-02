<?php

class Temando_Temando_Adminhtml_JsonController extends Mage_Adminhtml_Controller_Action {
    
    /**
     * Return JSON-encoded array of country regions
     *
     * @return string
     */
    public function countryRegionAction()
    {
        $arrRes = array();

        $countryId = $this->getRequest()->getParam('parent');
        $colRegions = Mage::getResourceModel('directory/region_collection')
            ->addCountryFilter($countryId)
            ->load();
            
	$arrRegions = $this->_toOptionArray($colRegions);
        if (!empty($arrRegions)) {
            foreach ($arrRegions as $region) {
                $arrRes[] = $region;
            }
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($arrRes));
    }
    
    private function _toOptionArray(Mage_Directory_Model_Resource_Region_Collection $collection)
    {
	$options = array();
	if(!empty($collection)) {
	    foreach($collection->getItems() as $region) {
		$options[$region->getRegionId()] = array(
		    'title' => $region->getDefaultName(),
		    'value' => $region->getCode(),
		    'label' => Mage::helper('temando')->__($region->getDefaultName())
		);
	    }
	}
	
	if (count($options) > 0) {
            array_unshift($options, array(
                'title '=> null,
                'value' => '',
                'label' => Mage::helper('temando')->__('-- Please select --')
            ));
        }
	
	return $options;
    }
    
}


