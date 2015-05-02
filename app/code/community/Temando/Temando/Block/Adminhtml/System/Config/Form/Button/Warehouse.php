<?php

class Temando_Temando_Block_Adminhtml_System_Config_Form_Button_Warehouse extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $this->setElement($element);
        $html = $this->getLayout()
                ->createBlock('adminhtml/widget_button')
                ->setType('button')->setClass('scalable go disabled')
                ->setLabel('Add New Warehouse')
                ->setOnClick('return false;')
                ->setTitle('Available in the Professional Plan')
                ->toHtml();
        return $html;
    }

}
