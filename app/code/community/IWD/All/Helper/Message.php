<?php
class IWD_All_Helper_Message extends Mage_Core_Helper_Abstract
{
    public function MarkMessageRead()
    {
        $collection = Mage::getModel('iwdall/notification')->getCollection();

        foreach ($collection as $message) {
            $this->updateStatus($message, 'is_read', true);
        }
    }

    public function removeMessage()
    {
        $collection = Mage::getModel('iwdall/notification')->getCollection();

        foreach ($collection as $message) {
            $this->updateStatus($message, 'is_remove', true);
        }
    }

    public function updateStatus($message, $type, $status)
    {
        $collection = Mage::getModel('adminnotification/inbox')->getCollection()->addFieldToFilter('title', array('eq' => $message->getTitle()));

        foreach ($collection as $item) {
            $item->setData($type, (int)$status);

            try {
                $item->save();
            } catch (Exception $e) {
                Mage::log($e);
            }
        }
    }

    public function updateStatusInternal($message, $type, $status)
    {
        $message->setData($type, (int)$status);

        try {
            $message->save();
        } catch (Exception $e) {
            Mage::log($e);
        }
    }
}