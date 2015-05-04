<?php
class IWD_All_Adminhtml_MessageController extends Mage_Adminhtml_Controller_Action
{
	public function markreadAction()
	{
		Mage::helper('iwdall/message')->MarkMessageRead();
		$this->loadLayout(false);
		$this->renderLayout();
	}
	
	public function removeAction()
	{
		Mage::helper('iwdall/message')->removeMessage();
		$this->loadLayout(false);
		$this->renderLayout();
	}
}