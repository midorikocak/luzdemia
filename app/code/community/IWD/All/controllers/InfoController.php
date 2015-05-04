<?php
class IWD_All_InfoController extends Mage_Core_Controller_Front_Action
{
    public function versionsAction()
    {
        $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
        $this->getResponse()->setHeader('Status', '404 File not found');

        $secret = $this->getRequest()->getParam('secret');
        if (isset($secret) && $secret == "123456") {
            $this->loadLayout();
            $block = $this->getLayout()->createBlock(
                'Mage_Core_Block_Template',
                'iwd_all_block_info',
                array('template' => 'iwd/all/info.phtml')
            );

            $this->getLayout()->getBlock('head')->setRobots("NOINDEX, NOFOLLOW");
            $this->getLayout()->getBlock('content')->append($block);
            $this->renderLayout();
        } else {
            $this->_forward('defaultNoRoute');
        }
    }
}