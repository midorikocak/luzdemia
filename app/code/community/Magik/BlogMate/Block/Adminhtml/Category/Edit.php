<?php

class Magik_BlogMate_Block_Adminhtml_Category_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{

		parent::__construct();
		$this->_objectId   = "id";
		$this->_blockGroup = "blogmate";
		$this->_controller = "adminhtml_category";
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
		if( Mage::registry("category_data") && Mage::registry("category_data")->getId() ){

			return Mage::helper("blogmate")->__("Edit Category '%s'", $this->htmlEscape(Mage::registry("category_data")->getTitle()));

		} 
		else{

			return Mage::helper("blogmate")->__("Add Category");

		}
	}
}