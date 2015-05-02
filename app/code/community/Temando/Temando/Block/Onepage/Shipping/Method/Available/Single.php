<?php

class Temando_Temando_Block_Onepage_Shipping_Method_Available_Single extends Temando_Temando_Block_Onepage_Shipping_Method_Available
{
    /**
     * One shipping method only?
     * 
     * @return boolean
     */
    public function getSole()
    {
        $groups = $this->getShippingRates();
	if(count($groups) == 1) {
	    $rates = array_pop($groups);
	    if(count($rates) == 1) return true;
	}
        return false;
    }
    
    /**
     * @return Temando_Temando_Model_Options
     */
    public function getOptions()
    {
        $options = Mage::registry('temando_current_options');
	if(!$options) {
	    $options = array();
	}
	
	return $options;
    }
    
    
    /**
     * Returns class for a shipping rate element (radio button)
     * (used in conjunction with extras tick boxes)
     * 
     * @param string $code Shipping rate code
     * @return string 
     */
    public function getClassFromRateCode($code)
    {
	$class = '';
	if(preg_match('/^temando_/', $code)) {
	    preg_match_all('/(insurance|carbonoffset|footprints)_(Y|N)/', $code, $matches);
	    $class = implode(' ', $matches[0]);
	}
	return $class;
    }
    
}
