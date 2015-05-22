<?php
class Magik_Socialbar_Block_Adminhtml_Socialbar_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("socialbar_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("socialbar")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("socialbar")->__("Item Information"),
				"title" => Mage::helper("socialbar")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("socialbar/adminhtml_socialbar_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
