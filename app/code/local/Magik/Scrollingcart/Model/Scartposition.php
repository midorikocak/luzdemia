<?php
class Magik_Scrollingcart_Model_Scartposition
{
    public function toOptionArray()
    {
        return array(
	   
	     array('value'=>'right', 'label'=>Mage::helper('scrollingcart')->__('Right')),
             array('value'=>'left', 'label'=>Mage::helper('scrollingcart')->__('Left')),
            
        );
    }

}
