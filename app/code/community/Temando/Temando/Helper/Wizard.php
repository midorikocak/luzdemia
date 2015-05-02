<?php

class Temando_Temando_Helper_Wizard extends Mage_Directory_Helper_Data {

    /**
     * Retrieve regions data json
     *
     * @return string
     */
    public function getRegionJson() {

        if (true/* empty($json) */) {
            $countryIds = array();
            foreach ($this->getCountryCollection() as $country) {
                $countryIds[] = $country->getCountryId();
            }
            $collection = Mage::getModel('directory/region')->getResourceCollection()
                    //->addCountryFilter($countryIds)
                    ->load();
            $regions = array(
                'config' => array(
                    'show_all_regions' => $this->getShowNonRequiredState(),
                    'regions_required' => $this->getCountriesWithStatesRequired()
                )
            );
            foreach ($collection as $region) {
                if (!$region->getRegionId()) {
                    continue;
                }
                $regions[$region->getCountryId()][strtoupper($region->getCode())] = array(
                    'code' => $this->__($region->getCode()),
                    'name' => $this->__($region->getName())
                );
            }
            if (count($regions) > 0) {
                array_unshift($regions, array(
                    'code' => '',
                    'name' => Mage::helper('temando')->__('-- Please select --')
                ));
            }
            $json = Mage::helper('core')->jsonEncode($regions);
        }

        return $json;
    }

}
