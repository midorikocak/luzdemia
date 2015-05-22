<?php


class Magik_BlogMate_Block_Adminhtml_Blog extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_blog";
	$this->_blockGroup = "blogmate";
	$this->_headerText = Mage::helper("blogmate")->__("Blog Manager");
	$this->_addButtonLabel = Mage::helper("blogmate")->__("Add New Post");
	parent::__construct();
	
	}

}