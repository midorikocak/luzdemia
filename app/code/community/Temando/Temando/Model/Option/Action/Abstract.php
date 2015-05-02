<?php

abstract class Temando_Temando_Model_Option_Action_Abstract extends Varien_Object
{
    
    /**
     * Applies the action to a given quote.
     *
     * @param Temando_Temando_Model_Quote $quote
     */
    abstract public function apply(&$quote);
    
}
