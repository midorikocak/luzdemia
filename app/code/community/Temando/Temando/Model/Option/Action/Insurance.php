<?php

class Temando_Temando_Model_Option_Action_Insurance extends Temando_Temando_Model_Option_Action_Abstract
{
    
    public function apply(&$quote)
    {
        /* @var $quote Temando_Temando_Model_Quote */
        $price = $quote->getTotalPrice();
        $insurance_price = $quote->getInsuranceTotalPrice();
        
        $quote->setTotalPrice($price + $insurance_price);
    }
    
}
