<?php
class IWD_All_Block_Adminhtml_Notification_Modal extends Mage_Core_Block_Template
{
    private $_messages = null;

    public function getNotification()
    {
        if ($this->_messages == null) {
            $collection = Mage::getModel('iwdall/notification')->getCollection();
            $collection->addFieldToFilter('main_table.view', '0');
            $this->_messages = $collection;

        }
        return $this->_messages;
    }

    public function getMarkReadUrl()
    {
        return Mage::helper('adminhtml')->getUrl('iwdall/adminhtml_message/markread', array('isAjax' => true));
    }

    public function getRemoveUrl()
    {
        return Mage::helper('adminhtml')->getUrl('iwdall/adminhtml_message/remove', array('isAjax' => true));
    }
}