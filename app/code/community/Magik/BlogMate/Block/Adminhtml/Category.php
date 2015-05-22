<?php


class Magik_BlogMate_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

		$this->_controller     = "adminhtml_category";
		$this->_blockGroup     = "blogmate";
		$this->_headerText     = Mage::helper("blogmate")->__("Category Manager");
		$this->_addButtonLabel = Mage::helper("blogmate")->__("Add New Category");
		parent::__construct();
		
	}

}