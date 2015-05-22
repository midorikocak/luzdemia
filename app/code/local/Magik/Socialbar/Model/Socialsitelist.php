<?php
class Magik_Socialbar_Model_Socialsitelist
{
    public function toOptionArray()
    { 
	  $collection = Mage::getModel('socialbar/socialsites')
			->getCollection();
			
	  $services = array();
	   
	  foreach ($collection as $cat) {
	    if($cat->getName() != ''){
            $services[] = ( array(
                'label' => (string) $cat->getName(),
                'value' => $cat->getId()
                    ));
	    }
	  }
	  return $services;

    }

}