<?php
class Magik_BlogMate_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function recursiveReplace($search, $replace, $subject){
		if(!is_array($subject))
		return $subject;
	
		foreach($subject as $key => $value)
		if(is_string($value))
		$subject[$key] = str_replace($search, $replace, $value);
		elseif(is_array($value))
		$subject[$key] = self::recursiveReplace($search, $replace, $value);
	
		return $subject;
	}

	public function getcattree($id) {
	    $home_url = Mage::helper('core/url')->getHomeUrl();
	    $storeId = Mage::app()->getStore()->getStoreId();
	    $collection = Mage::getModel('blogmate/category')
			->getCollection()
			->addFieldToFilter('status',array('eq'=>'1'))
			->addFieldToFilter('cat_pid',array('eq'=>$id))
			->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')));

		echo "<ul>";
		foreach ($collection as $cat) {
		 // $category_url = $home_url.'blog/view/category/c/'.$cat['title_slug'].'/';

		  $urlKey = $cat['title_slug'];
		  $curl = Mage::getUrl('blog/view/category/c/'.$urlKey, array('_direct' => $urlKey)); 
		  $cnt=Mage::getModel('blogmate/blog')->chkIfExists('index/'.$urlKey,$urlKey);
		  $store_id=Mage::app()->getStore()->getId();
		  if($cnt==0 && ($urlKey!=''))
		  {	
			  $curl1=$cat['title_slug'];
			  $rewrite = Mage::getModel('core/url_rewrite');
			  $rewrite->setStoreId($store_id)
			  ->setIdPath('index/'.$urlKey)
			  ->setRequestPath($curl1)
			  ->setTargetPath('blog/view/category/c/'.$urlKey)
			  ->setIsSystem(true)
			  ->save();

		  }
						  
		  $category_url =$curl;
		  if($cat['cat_pid'] == 0){
		    echo "<li class='cat-item cat-item-19599'><a href=".$category_url.">".ucfirst($cat['title'])."</a></li>";

		

		    }
		  else if($cat['subcategory'] == 0){
		    echo "<li class='cat-item cat-item-19599'>&nbsp;<a href=".$category_url.">". ucfirst($cat['title'])."</a></li>";

		    }
		    else{  echo "<li class='cat-item cat-item-19599'>&nbsp;&nbsp;<a href=".$category_url.">".ucfirst($cat['title'])."</a></li>";}

		    echo $this->getcattree($cat['id']);
		  }
		echo "</ul>";
	  
      }


	  

}
