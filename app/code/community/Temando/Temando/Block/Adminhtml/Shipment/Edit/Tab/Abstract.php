<?php

abstract class Temando_Temando_Block_Adminhtml_Shipment_Edit_Tab_Abstract extends Mage_Adminhtml_Block_Template
{
    
    protected $_helper = null;

    public function __construct()
    {
        parent::__construct();
        $this->setParentBlock(Mage::getBlockSingleton('temando/adminhtml_shipment_edit'));
    }
    
    /**
     * Gets the shipment being edited.
     *
     * @return Temando_Temando_Model_Shipment
     */
    public function getShipment()
    {
        return $this->getParentBlock()->getShipment();
    }
    
    /**
     * Gets the saved Temando quotes for this order from the database.
     *
     * @return Temando_Temando_Model_Mysql4_Quote_Collection
     */
    public function getQuotes()
    {
        return $this->getShipment()->getQuotes(true);
    }
    
    public function formatCurrency($price)
    {
        return Mage::helper('core')->currency($price);
    }
    
    public function getWeightUnitText($unit = null)
    {
        if (!$unit) {
            $unit = $this->getTemandoHelper()->getConfigData('units/weight');
        }
        return Mage::getModel('temando/system_config_source_unit_weight')
            ->getBriefOptionLabel($unit);
    }
    
    public function getMeasureUnitText($unit = null)
    {
        if (!$unit) {
            $unit = $this->getTemandoHelper()->getConfigData('units/measure');
        }
        return Mage::getModel('temando/system_config_source_unit_measure')
            ->getBriefOptionLabel($unit);
    }
    
    public function getTemandoHelper() {
        if (!$this->_helper) {
            $this->_helper = Mage::helper('temando');
        }
        return $this->_helper;
    }
    
}
