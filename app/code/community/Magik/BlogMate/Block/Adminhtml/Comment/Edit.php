<?php

class Magik_BlogMate_Block_Adminhtml_Comment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{

		parent::__construct();
		$this->_objectId = "id";
		$this->_blockGroup = "blogmate";
		$this->_controller = "adminhtml_comment";
		$this->_updateButton("save", "label", Mage::helper("blogmate")->__("Save Item"));
		$this->_updateButton("delete", "label", Mage::helper("blogmate")->__("Delete Item"));

		$this->_addButton("saveandcontinue", array(
			"label"     => Mage::helper("blogmate")->__("Save And Continue Edit"),
			"onclick"   => "saveAndContinueEdit()",
			"class"     => "save",
			), -100);



		$this->_formScripts[] = "

		function saveAndContinueEdit(){
			editForm.submit($('edit_form').action+'back/edit/');
		}
		";
	}

	public function getHeaderText()
	{
		if( Mage::registry("comment_data") && Mage::registry("comment_data")->getId() ){

			return Mage::helper("blogmate")->__("Edit Comment '%s'", $this->htmlEscape(Mage::registry("comment_data")->getId()));

		} 
		else{

			return Mage::helper("blogmate")->__("Add Comment");

		}
	}
}