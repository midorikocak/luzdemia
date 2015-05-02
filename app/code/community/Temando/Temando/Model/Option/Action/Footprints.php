<?php

class Temando_Temando_Model_Option_Action_Footprints extends Temando_Temando_Model_Option_Action_Abstract
{
    
    public function apply(&$quote)
    {
        /* @var $quote Temando_Temando_Model_Quote */
        $price = $quote->getTotalPrice();
        $footprints_price = $quote->getFootprintsTotalPrice();
        
        $quote->setTotalPrice($price + $footprints_price);
    }
    
}
