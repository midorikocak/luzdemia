<?php   
class Magik_BlogMate_Block_Index extends Mage_Core_Block_Template{   

	public function __construct() { 
		$storeId=Mage::app()->getStore()->getStoreId();
		parent::__construct();
		$collection = Mage::getModel('blogmate/blog')->getCollection()
		->addFieldToFilter('status',array('eq'=>'1'))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('display_order','ASC');
		$this->setCollection($collection);
	}

	protected function _prepareLayout() {
		parent::_prepareLayout();

		$pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
		$pager->setAvailableLimit(array(5=>5, 10=>10, 15=>15, 20=>20,'all'=>'All'));
		$pager->setShowPerPage(true);
		$pager->setShowAmounts(true);
		$pager->setCollection($this->getCollection());
		$this->setChild('pager', $pager);
		$this->getCollection()->load();
		return $this;
	}

	public function getPagerHtml() {
		return $this->getChildHtml('pager');
	} 


	public function getAllCategories(){
		return $categories = Mage::getModel('blogmate/category')->getAllBlogCategories();
	}

	public function getPostOfCategory($category_id){
		return $post_of_category = Mage::getModel('blogmate/blog')->getPostOfCategory($category_id);
	}

	public function getPostOfCategoryByCategorySlug($category_slug){
		return $post_of_category = Mage::getModel('blogmate/blog')->getPostOfCategoryByCategorySlug($category_slug);
	}

	public function getAllPost(){
		return $all_post = Mage::getModel('blogmate/blog')->getAllPost();
	}

	public function getCategoryById($category_id){
		return $all_post = Mage::getModel('blogmate/category')->getCategoryById($category_id);
	}

	public function getRecentPost(){
		return $recent_post = Mage::getModel('blogmate/blog')->getRecentPost();
	}

	public function getPostDataBySlug($post_title_slug){
		$post_data = Mage::getModel('blogmate/blog')->getPostDataBySlug($post_title_slug);
		return $post_data;
	}

	public function getPostTitleBySlug($post_title_slug){
		$post_data = Mage::getModel('blogmate/blog')->getPostTitleBySlug($post_title_slug);
		return $post_data;
	}

	public function getCategoryIdBySlug($category_slug){
		$post_data = Mage::getModel('blogmate/category')->getCategoryIdBySlug($category_slug);
		return $post_data;
	}
}