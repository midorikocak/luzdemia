<?php
class Magik_BlogMate_Block_Adminhtml_Comment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{

		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset("blogmate_form", array("legend"=>Mage::helper("blogmate")->__("Item information")));

		$fieldset->addField("blog_id", "text", array(
			"label"    => Mage::helper("blogmate")->__("Blog Id"),					
			"class"    => "required-entry",
			"required" => true,
			"name"     => "blog_id",
			"disabled"	 => true,
			));

		$fieldset->addField("user_name", "text", array(
			"label"    => Mage::helper("blogmate")->__("User Name"),					
			"class"    => "required-entry",
			"required" => true,
			"name"     => "user_name",
			));

		$fieldset->addField("user_email", "text", array(
			"label"    => Mage::helper("blogmate")->__("User Email"),					
			"class"    => "required-entry",
			"required" => true,
			"name"     => "user_email",
			));

		$fieldset->addField("comment", "textarea", array(
			"label"    => Mage::helper("blogmate")->__("Comment"),					
			"class"    => "required-entry",
			"required" => true,
			"name"     => "comment",
			));

		
		$fieldset->addField("status", "select", array(
			"label" => Mage::helper("blogmate")->__("Status"),
			"name" => "status",
			"value"=>2,
			"values"    => array(array('value' => 2,'label' => Mage::helper('blogmate')->__('Disabled'),),
					     array('value' => 1,'label'     => Mage::helper('blogmate')->__('Enabled'),),
						      ),
			"class"    => "required-entry",
			"required" => true,
		)); 


		if (Mage::getSingleton("adminhtml/session")->getCommentData())
		{
			$form->setValues(Mage::getSingleton("adminhtml/session")->getCommentData());
			Mage::getSingleton("adminhtml/session")->setCommentData(null);
		} 
		elseif(Mage::registry("comment_data")) {
			$form->setValues(Mage::registry("comment_data")->getData());
		}
		return parent::_prepareForm();
	}
}
