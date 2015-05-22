<?php

class Magik_BlogMate_Model_Blog extends Mage_Core_Model_Abstract
{
	protected function _construct(){

		$this->_init("blogmate/blog");

	}

	public function getPostOfCategory($category_id) {
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection = $this->getCollection()
		->addFieldToFilter('status',array('eq'=>'1'))
		->addFieldToFilter('categories_selected',array(array('like'=>'%'.$category_id.'%')))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('display_order','ASC');
		$post_of_category = $collection->getData();

		return $post_of_category;
	}

	public function getAllPost() {
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection = $this->getCollection()
		->addFieldToFilter('status',array('eq'=>'1'))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('display_order','ASC');
		$all_post = $collection->getData();
		return $all_post;
	}

	public function getRecentPost() {
		//$limit = $comment_config = Mage::getStoreConfig('blogmate/blog_setting/numrecentpost');
		$limit=5;
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection = $this->getCollection()
		->addFieldToFilter('status', array('eq'=>'1'))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		//->setPageSize($limit)
		->addOrder('created_at','DESC');
		$all_post = $collection->getData();

		return $all_post;
	}

	public function getPostDataBySlug($post_title_slug) {
		$storeId = Mage::app()->getStore()->getStoreId();
		$post_data = $this->getCollection()
		->addFieldToFilter('title_slug', array('eq'=>$post_title_slug))
		->addFieldToFilter('status', array('eq'=>'1'))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('created_at','DESC');
		$post_data = $post_data->getData();
		return $post_data;
	}

	public function getBlogIdBySlug($post_title_slug) {
		$storeId = Mage::app()->getStore()->getStoreId();
		$post_id = $this->getCollection()
		->addFieldToSelect('id')
		->addFieldToFilter('title_slug', array('eq'=>$post_title_slug))
		->addFieldToFilter('status', array('eq'=>'1'))
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('created_at','DESC');
		$post_id = $post_id->getData();
		return $post_id[0]['id'];
	}

	public function getPostControllerData($post_title_slug) {
		$storeId = Mage::app()->getStore()->getStoreId();
		$post_title = $this->getCollection()
		->addFieldToFilter('title_slug', array('eq'=>$post_title_slug))
		->addFieldToFilter('status', array('eq'=>'1'))
		->addFieldToSelect('title')
		->addFieldToSelect('meta_keywords')
		->addFieldToSelect('meta_description')
		->addFieldToFilter('stores_selected',array(array('like'=>'%'.$storeId.'%'), array('like'=>'%0%')))
		->addOrder('created_at','DESC');
		$post_title = $post_title->getData();
		return $post_title[0];
	}
  
	public function chkIfExists($idpath,$req)
    {
	$resource = Mage::getSingleton('core/resource');
	$read= $resource->getConnection('core_read');		
	$Table=(string)Mage::getConfig()->getTablePrefix().'core_url_rewrite';
	$store_id=Mage::app()->getStore()->getId();
	$select2 = "select * from ".$Table." where id_path='".$idpath."' AND request_path='".$req."' AND store_id='".$store_id."'" ;
	$result2 = $read->fetchAll($select2);
	return count($result2);
    }	

}
