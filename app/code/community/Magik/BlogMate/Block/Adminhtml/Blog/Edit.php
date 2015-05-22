<?php

class Magik_BlogMate_Block_Adminhtml_Blog_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

	public function __construct()
	{

		parent::__construct();
		$this->_objectId   = "id";
		$this->_blockGroup = "blogmate";
		$this->_controller = "adminhtml_blog";
		$this->_updateButton("save", "label", Mage::helper("blogmate")->__("Save Post"));
		$this->_updateButton("delete", "label", Mage::helper("blogmate")->__("Delete Post"));

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
		if( Mage::registry("blog_data") && Mage::registry("blog_data")->getId() ){

			return Mage::helper("blogmate")->__("Edit Blog '%s'", $this->htmlEscape(Mage::registry("blog_data")->getTitle()));

		} 
		else{

			return Mage::helper("blogmate")->__("Add Blog");

		}
	}
}