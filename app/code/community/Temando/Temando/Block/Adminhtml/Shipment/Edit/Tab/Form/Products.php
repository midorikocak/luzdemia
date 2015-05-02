<?php

class Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Form_Products extends Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Abstract
{
	
    /**
     * Gets a Magento catalog product belonging to a Magento order item.
     *
     * @param Mage_Sales_Model_Order_Item $item the Magento order item
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProductFromItem(Mage_Sales_Model_Order_Item $item)
    {
        return Mage::getModel('catalog/product')->load($item->getProductId());
    }
    
}
