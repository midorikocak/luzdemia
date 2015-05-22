<?php
class Magik_BlogMate_Block_Adminhtml_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId("comment_tabs");
		$this->setDestElementId("edit_form");
		$this->setTitle(Mage::helper("blogmate")->__("Blog Comment"));
	}
	protected function _beforeToHtml()
	{
		$this->addTab("form_section", array(
			"label" => Mage::helper("blogmate")->__("Comment Information"),
			"title" => Mage::helper("blogmate")->__("Comment Information"),
			"content" => $this->getLayout()->createBlock("blogmate/adminhtml_comment_edit_tab_form")->toHtml(),
			));
		return parent::_beforeToHtml();
	}

}
