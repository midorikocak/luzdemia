<?php


class Magik_Socialbar_Block_Adminhtml_Socialbar extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_socialbar";
	$this->_blockGroup = "socialbar";
	$this->_headerText = Mage::helper("socialbar")->__("Social Bar Manager");
	$this->_addButtonLabel = Mage::helper("socialbar")->__("Add New Item");
	parent::__construct();
	
	}

}