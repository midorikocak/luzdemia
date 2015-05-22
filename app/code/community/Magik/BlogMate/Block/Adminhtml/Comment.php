<?php


class Magik_BlogMate_Block_Adminhtml_Comment extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_comment";
	$this->_blockGroup = "blogmate";
	$this->_headerText = Mage::helper("blogmate")->__("Comment Manager");
	// $this->_addButtonLabel = Mage::helper("blogmate")->__("Add New Comment");
	parent::__construct();
	
	}

	protected function _prepareLayout() {
        $this->_removeButton('add');
        return parent::_prepareLayout();
    }

}