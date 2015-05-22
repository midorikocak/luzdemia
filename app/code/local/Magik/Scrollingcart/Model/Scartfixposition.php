<?php
class Magik_Scrollingcart_Model_Scartfixposition
{
    public function toOptionArray()
    {
        return array(
	   
	     array('value'=>'true', 'label'=>Mage::helper('scrollingcart')->__('True')),
             array('value'=>'false', 'label'=>Mage::helper('scrollingcart')->__('False')),
            
        );
    }

}
