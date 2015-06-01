<?php
/**
 * @package
 * @author Stefan richter (richter@aromicon.com)
 * @license aromicon gmbh 2013
 */
class Aromicon_Gua_Model_System_Config_Source_Orderid
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'entity_id', 'label'=>Mage::helper('aromicon_gua')->__('ID')),
            array('value' => 'increment_id', 'label'=>Mage::helper('aromicon_gua')->__('Increment ID')),
        );
    }
}