<?php
class Magik_BlogMate_Model_Categorylist
{
    public function toOptionArray()
    {  
	  $storeId = Mage::app()->getStore()->getStoreId();
	  $collection = Mage::getModel('blogmate/category')
			->getCollection()
			->addFieldToFilter('status',array('eq'=>'1'))
			->addFieldToFilter('cat_pid', array('in' => array(0))) 
			->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')));
			

	  $categories = array();
	  $categories[]=array('label'=> 'Root Category','value' => '0'); 
	  foreach ($collection as $cat) {
	    if($cat->getTitle() != ''){
            $categories[] = ( array(
                'label' => (string) $cat->getTitle(),
                'value' => $cat->getId()
                    ));
	    }
	  }
	  return $categories;


	     

    }

}