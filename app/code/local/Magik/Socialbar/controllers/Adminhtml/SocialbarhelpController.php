<?php
class Magik_Socialbar_Adminhtml_SocialbarhelpController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('socialbar/socialfloaterhelp')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }
    public function indexAction() {
        $this->_initAction();       
        $this->_addContent($this->getLayout()->createBlock('socialbar/adminhtml_socialbarhelp'));
        $this->renderLayout();
    }
} 
 
 
