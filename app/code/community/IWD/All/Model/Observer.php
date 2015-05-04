<?php
class IWD_All_Model_Observer
{
    public function preDispatch(Varien_Event_Observer $observer)
    {
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            $feedModel = Mage::getModel('iwdall/feed');
            /* @var $feedModel Mage_AdminNotification_Model_Feed */

            $feedModel->checkUpdate();
        }
    }
}
