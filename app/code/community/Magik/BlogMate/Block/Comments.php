<?php   
class Magik_BlogMate_Block_Comments extends Mage_Core_Block_Template{   

	public function __construct() { 
		$storeId=Mage::app()->getStore()->getStoreId();
		parent::__construct();
		
		$post_slug = $this->getRequest()->getParam('p');
		$blog_id = Mage::getModel('blogmate/blog')->getBlogIdBySlug($post_slug);
		$collection = Mage::getModel('blogmate/comment')->getCollection()
		->addFieldToFilter('status',array('eq'=>'1'))
		->addFieldToFilter('blog_id',array('eq'=>$blog_id));
		$this->setCollection($collection);
	}

	// protected function _prepareLayout() {
	// 	parent::_prepareLayout();
	// 	$pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
	// 	$pager->setAvailableLimit(array(5=>5, 10=>10, 15=>15, 20=>20,'all'=>'All'));
	// 	$pager->setShowPerPage(true);
	// 	$pager->setShowAmounts(true);
	// 	$pager->setCollection($this->getCollection());
	// 	$this->setChild('pager', $pager);
	// 	$this->getCollection()->load();
	// 	return $this;
	// }

	// public function getPagerHtml() {
	// 	return $this->getChildHtml('pager');
	// } 

}