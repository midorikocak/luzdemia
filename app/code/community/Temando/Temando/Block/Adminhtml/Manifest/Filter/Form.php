<?php

class Temando_Temando_Block_Adminhtml_Manifest_Filter_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Add fields to base fieldset which are general to sales reports
     *
     * @return Mage_Sales_Block_Adminhtml_Report_Filter_Form
     */
    protected function _prepareForm()
    {
        $actionUrl = $this->getUrl('*/*/add');
        $form = new Varien_Data_Form(
            array('id' => 'add_form', 'action' => $actionUrl, 'method' => 'post')
        );
        $htmlIdPrefix = 'add_manifest_';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('temando')->__('Create New Manifest - Please note, to manifest you must have a permanent pickup. Contact <a href="mailto:sales@temando.com">sales@temando.com</a> for more information.')));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('warehouse_id', 'select', array(
            'name' => 'warehouse_id',
            'options' => Mage::helper('temando')->getLocationList(),
            'label' => Mage::helper('temando')->__('Location'),
            'title' => Mage::helper('temando')->__('Location')
        ));

        $fieldset->addField('carrier_id', 'multiselect', array(
            'name'      => 'carrier_id[]',
            'values'    => Mage::getModel('temando/shipping_carrier_temando_source_method')->toOptionArray(),
            'label'     => Mage::helper('temando')->__('Carrier'),
            'required'  => true
        ));

        $fieldset->addField('from', 'date', array(
            'name'      => 'from',
            'format'    => $dateFormatIso,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'label'     => Mage::helper('temando')->__('Date'),
            'title'     => Mage::helper('temando')->__('Date'),
            'time'      => false,
            'required'  => true
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}