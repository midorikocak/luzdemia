<?php

class Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Form_Insurance extends Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Abstract
{
    
    public function getTotalGoodsValue()
    {
        $order_id = $this->getShipment()->getOrderId();
        $goods_value = 0;
        foreach (Mage::getModel('sales/order')->load($order_id)->getAllVisibleItems() as $item) {
            $value = $item->getValue();
            if (!$value) {
                $value = $item->getRowTotal();
            }
            if (!$value) {
                $qty = $item->getQty();
                if (!$qty) {
                    $qty = $item->getQtyOrdered();
                }
                $value = $item->getPrice() * $qty;
            }

            $goods_value += $value;
        }

        return $goods_value;
    }

    public function isAdminSelect()
    {
        if ($this->getShipment() && !is_null(Mage::getSingleton('adminhtml/session')->getData('insurance_' . $this->getShipment()->getId()))) {
            return true;
        }

        return false;
    }
    
}
