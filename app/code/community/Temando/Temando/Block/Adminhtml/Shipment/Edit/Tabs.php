<?php

class Temando_Temando_Block_Adminhtml_Shipment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('temando_shipment_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('temando')->__('Shipment View'));
    }

}
