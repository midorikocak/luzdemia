<?php
class Midori_Conversion_Model_Observer
{
    public function userLogin(Varien_Event_Observer $observer)
    {
       // $event = $observer->getEvent();
       // $data = $event->getData();
       // $layout = $observer->getEvent()->getLayout();
       //$layout = $observer->getEvent()->getLayout();
       Mage::log(
                   'logged',
                   null,
                   'customer-login.log'
               );
       Mage::getModel('customer/session')->setUserLogin(true);
       
       // $layout = Mage::app()->getLayout();
       // $layout->getUpdate()->addUpdate('
       // reference name="head">
       //     <block type="core/text" name="myjs">
       //         <action method="setText"><text><![CDATA[<script type="text/javascript">
       //         console.log("user logged in")
       // </script>]]></text></action>
       //     </block>
       // </reference>');
       // $layout->getUpdate()->load();
       // $layout->generateXml();

       //$update = $observer->getEvent()->getLayout()->getUpdate();
       //$update->addHandle('midori_conversion');
        //$layout = $controller->getLayout();
        // $block = $layout->createBlock('core/text');
        // $block->setText(
        // '<script type="text/javascript">
        //    console.log("user logged in");
        // </script>'
        // );
        // $layout->getBlock('head')->append($block);          
    }
}  