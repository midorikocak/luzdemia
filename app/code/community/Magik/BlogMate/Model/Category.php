<?php

class Magik_BlogMate_Model_Category extends Mage_Core_Model_Abstract
{
	protected function _construct() {

		$this->_init("blogmate/category");

	}

	public function getAllBlogCategories() {
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection = $this
		->getCollection()
		->addFieldToFilter('status',array('eq'=>'1'))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('display_order','ASC');
		$categories = $collection->getData();
		return $categories;
	}

	public function getCategoryById($category_id) {
		// echo $category_id;
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection = $this
		->getCollection()
		->addFieldToFilter('status',array('eq'=>'1'))
		->addFieldToFilter('id',array('eq'=>$category_id))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('display_order','ASC');
		$categories = $collection->getData();
		// print_r($categories);
		return $categories;
	}

	public function getCategoryIdBySlug($category_slug) {
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection = $this
		->getCollection()
		->addFieldToFilter('status',array('eq'=>'1'))
		->addFieldToSelect('id')
		->addFieldToFilter('title_slug',array('eq'=>$category_slug))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('display_order','ASC');
		$category_id = $collection->getData();
		return $category_id[0]['id'];
	}

	public function getCategoryControllerData($category_title_slug) {
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection = $this
		->getCollection()
		->addFieldToFilter('status',array('eq'=>'1'))
		->addFieldToSelect('title')
		->addFieldToSelect('meta_keywords')
		->addFieldToSelect('meta_description')
		->addFieldToFilter('title_slug',array('eq'=>$category_title_slug))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('display_order','ASC');
		$categories_title = $collection->getData();
		return $categories_title[0];
	}
}
