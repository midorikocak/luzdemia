<?php
class Midori_Conversion_Model_Observer
{
    public function userLogin(Varien_Event_Observer $observer)
    {
       // $event = $observer->getEvent();
       // $data = $event->getData();

       Mage::getModel('customer/session')->setUserLogin(true);
        
    }
}  