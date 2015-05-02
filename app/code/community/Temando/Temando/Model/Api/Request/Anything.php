<?php

class Temando_Temando_Model_Api_Request_Anything extends Mage_Core_Model_Abstract
{   
    const GOODS_CLASS    = 'General Goods';
    const GOODS_SUBCLASS = 'Household Goods';
    const PALLET_TYPE	 = 'Plain';
    const PALLET_NATURE  = 'Not Required';
    
    /**
     * @var Mage_Sales_Model_Order_Item
     */
    protected $_item = null;
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/api_request_anything');
    }
    
    public function setItem($item)
    {
        if ($item instanceof Mage_Sales_Model_Quote_Item || $item instanceof Mage_Sales_Model_Order_Item || 
		$item instanceof Mage_Sales_Model_Quote_Address_Item || $item instanceof Temando_Temando_Model_Box) {
            $this->_item = $item;
        }
        return $this;
    }
    
    /**
     * Gets the order item for this Anything object.
     *
     * @return Mage_Sales_Model_Order_Item
     */
    public function getItem()
    {
        if ($this->_item) {
            return $this->_item;
        }
        return false;
    }
    
    public function toRequestArray()
    {
        if (!$this->validate()) {
            return false;
        }
                
        if ($this->_item instanceof Temando_Temando_Model_Box) {
            $anything = array(
                'class'         => 'General Goods',
                'subclass'      => 'Household Goods',
                'packaging'     => Mage::getModel('temando/system_config_source_shipment_packaging')->getOptionLabel($this->_item->getPackaging()),
                'quantity'      => (int)($this->_item->getQty()),
                'distanceMeasurementType' => Temando_Temando_Model_System_Config_Source_Unit_Measure::CENTIMETRES,
                'weightMeasurementType'   => Temando_Temando_Model_System_Config_Source_Unit_Weight::GRAMS,
                'weight'        => Mage::helper('temando')->getWeightInGrams($this->_item->getWeight(), $this->_item->getWeightUnit()),
                'length'        => Mage::helper('temando')->getDistanceInCentimetres($this->_item->getLength(), $this->_item->getMeasureUnit()),
                'width'         => Mage::helper('temando')->getDistanceInCentimetres($this->_item->getWidth(), $this->_item->getMeasureUnit()),
                'height'  	=> Mage::helper('temando')->getDistanceInCentimetres($this->_item->getHeight(), $this->_item->getMeasureUnit()),
                'qualifierFreightGeneralFragile' => $this->_item->getFragile() == '1' ? 'Y' : 'N',
                'description'   => $this->_item->getComment()
            );
	    if($this->_item->getPackaging() == Temando_Temando_Model_System_Config_Source_Shipment_Packaging::PALLET) {
		$anything['palletType']   = self::PALLET_TYPE;
		$anything['palletNature'] = self::PALLET_NATURE;
	    }
            
        } else {
	    
	    Mage::helper('temando')->applyTemandoParamsToItem($this->_item);
	    $anything = array(
		'class'	=> 'General Goods',
		'subclass'	=> 'Household Goods',
		'packaging'	=> Mage::getModel('temando/system_config_source_shipment_packaging')->getOptionLabel($this->_item->getTemandoPackaging()),
		'quantity'	=> (int)($this->_item->getQty() ? $this->_item->getQty() : $this->_item->getQtyOrdered()),
		'distanceMeasurementType' => Temando_Temando_Model_System_Config_Source_Unit_Measure::CENTIMETRES,
		'weightMeasurementType' => Temando_Temando_Model_System_Config_Source_Unit_Weight::GRAMS,
		'weight'	=> Mage::helper('temando')->getWeightInGrams($this->_item->getWeight(),Mage::helper('temando')->getConfigData('units/weight')),
		'length'	=> Mage::helper('temando')->getDistanceInCentimetres($this->_item->getTemandoLength(), Mage::helper('temando')->getConfigData('units/measure')),
		'width'		=> Mage::helper('temando')->getDistanceInCentimetres($this->_item->getTemandoWidth(), Mage::helper('temando')->getConfigData('units/measure')),
		'height'	=> Mage::helper('temando')->getDistanceInCentimetres($this->_item->getTemandoHeight(), Mage::helper('temando')->getConfigData('units/measure')),
		'qualifierFreightGeneralFragile' => $this->_item->getTemandoFragile() == '1' ? 'Y' : 'N',
		'description'	=> $this->_item->getName()
	    );
	    if($this->_item->getTemandoPackaging() == Temando_Temando_Model_System_Config_Source_Shipment_Packaging::PALLET) {
		$anything['palletType']   = self::PALLET_TYPE;
		$anything['palletNature'] = self::PALLET_NATURE;
	    }
	    
	} 
        return $anything;
    }    
    public function validate()
    {
        return $this->_item instanceof Mage_Sales_Model_Quote_Item ||
            $this->_item instanceof Mage_Sales_Model_Order_Item ||
	    $this->_item instanceof Mage_Sales_Model_Quote_Address_Item ||
            $this->_item instanceof Temando_Temando_Model_Box;
    }

}
