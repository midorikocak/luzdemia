<?php

/**
 * @method string getShipmentId()
 * @method string getComment()
 * @method string getQty()
 * @method string getLength()
 * @method string getWidth()
 * @method string getHeight()
 * @method string getMeasureUnit()
 * @method string getWeight()
 * @method string getWeightUnit()
 * @method string getFragile()
 *
 * @method Temando_Temando_Model_Box setShipmentId()
 * @method Temando_Temando_Model_Box setComment()
 * @method Temando_Temando_Model_Box setQty()
 * @method Temando_Temando_Model_Box setLength()
 * @method Temando_Temando_Model_Box setWidth()
 * @method Temando_Temando_Model_Box setHeight()
 * @method Temando_Temando_Model_Box setMeasureUnit()
 * @method Temando_Temando_Model_Box setWeight()
 * @method Temando_Temando_Model_Box setWeightUnit()
 * @method Temando_Temando_Model_Box setFragile()
 */
class Temando_Temando_Model_Box extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/box');
    }
    
}
