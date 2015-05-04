<?php
class IWD_All_Adminhtml_ConflictsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('iwdall')
            ->_title($this->__('IWD - Extensions Conflict'))
            ->_addBreadcrumb(
                Mage::helper('iwdall')->__('IWD Extensions Conflict'),
                Mage::helper('iwdall')->__('IWD Extensions Conflict')
            );

        $this->_addContent(
            $this->getLayout()->createBlock('iwdall/adminhtml_conflicts', 'iwd_extensions_conflicts')
        );

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout()
            ->getResponse()
            ->setBody($this->getLayout()
                ->createBlock('iwdall/adminhtml_conflicts_grid')->toHtml()
            );
    }
}