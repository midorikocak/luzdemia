<?php
class Magik_BlogMate_Adminhtml_BlogmatebackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Backend Blog Title"));
	   $this->renderLayout();
    }
}