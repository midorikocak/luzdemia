<?php
class Magik_Socialbar_Model_Socialcategorylist
{
    public function toOptionArray()
    { 
	  $collection = Mage::getModel('catalog/category')
			->getCollection()
			->addAttributeToSelect('*')
			->addIsActiveFilter()
			->addOrderField('name');

	  $categories = array();
	   
	  foreach ($collection as $cat) {
	    if($cat->getName() != ''){
            $categories[] = ( array(
                'label' => (string) $cat->getName(),
                'value' => $cat->getId()
                    ));
	    }
	  }
	  return $categories;

    }

}