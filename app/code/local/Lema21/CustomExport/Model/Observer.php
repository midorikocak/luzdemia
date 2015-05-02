<?php

class Lema21_CustomExport_Model_Observer {
    
    public function includeOption($observer)
    {
        // Get code of grid
        $idBlockObserver = $observer->getEvent()->getBlock()->getId();

        if ($idBlockObserver=="sales_order_grid" ) {
            
            // copy+paste by "delete orders" module, thanks Stefan Wieczorek
            $block = $observer->getEvent()
                ->getBlock()
                ->getMassactionBlock();
            
            if ($block) {
                $block->addItem('custom_export', array(
                    'label'=> Mage::helper('custom_export')->__('Custom Export Data to CSV'),
                    'url'  => Mage::getUrl('lema21_custom_export', array('_secure'=>true)),
                ));
            }
        }
    }

}