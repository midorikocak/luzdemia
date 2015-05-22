<?php
class Magik_BlogMate_Block_Adminhtml_Blog_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId("blog_tabs");
		$this->setDestElementId("edit_form");
		$this->setTitle(Mage::helper("blogmate")->__("Blog Information"));
	}
	protected function _beforeToHtml()
	{
		$this->addTab("form_section", array(
			"label"   => Mage::helper("blogmate")->__("Blog Information"),
			"title"   => Mage::helper("blogmate")->__("Blog Information"),
			"content" => $this->getLayout()->createBlock("blogmate/adminhtml_blog_edit_tab_form")->toHtml(),
			));
		return parent::_beforeToHtml();
	}

}
