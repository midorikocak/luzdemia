<?php
class Magik_BlogMate_Model_Blogcategorylist
{
    public function toOptionArray()
    {  
	  
	  $collection = Mage::getModel('blogmate/category')->getCollection();
	  $categories = array();
	   
	  foreach ($collection as $cat) {

	      if( ($cat['cat_pid'] == 0)){
		$categories[] = (array(
						'label' => (string) $cat['title'] , 
						'value' => $cat['id']
						
					  ));
			
			$subcollection = Mage::getModel('blogmate/category')
					->getCollection()
					->addFieldToFilter('cat_pid',array('eq'=>$cat['id']))
					->addFieldToFilter('subcategory',array('eq'=>'0'));
					
					foreach ($subcollection as $subcat) {
					  $categories[] = (array(
							      'label' => (string) '-'.$subcat['title'] , 
							      'value' => $subcat['id']
							      
							));  
					$subsubcollection = Mage::getModel('blogmate/category')
					->getCollection()
					->addFieldToFilter('subcategory',array('eq'=>$subcat['id']));
					
					      foreach ($subsubcollection as $subsubcat) {
						    $categories[] = (array(
								  'label' => (string) '---'.$subsubcat['title'] , 
								  'value' => $subsubcat['id']
								  
							    )); 
					      }
					}

		}
		
		    
	  }

	return $categories;

    }

}