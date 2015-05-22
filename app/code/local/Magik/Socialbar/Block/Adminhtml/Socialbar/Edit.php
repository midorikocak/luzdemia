<?php
	
class Magik_Socialbar_Block_Adminhtml_Socialbar_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "socialbar";
				$this->_controller = "adminhtml_socialbar";
				$this->_updateButton("save", "label", Mage::helper("socialbar")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("socialbar")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("socialbar")->__("Save And Continue Edit"),
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
				if( Mage::registry("socialbar_data") && Mage::registry("socialbar_data")->getId() ){

				    return Mage::helper("socialbar")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("socialbar_data")->getName()));

				} 
				else{

				     return Mage::helper("socialbar")->__("Add Item");

				}
		}
}