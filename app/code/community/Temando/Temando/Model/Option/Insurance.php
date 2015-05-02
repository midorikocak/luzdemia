<?php

class Temando_Temando_Model_Option_Insurance extends Temando_Temando_Model_Option_Boolean
{
    
    protected $_id = 'insurance';
    protected $_name = 'Include Delivery Insurance';
    protected $_action_type = 'insurance';
    protected $_desc = 'For your convenience, the company arranging the transport of your newly purchased item provides you with insurance for a nominal fee.';
    
    /**
     * Sets the current "setting" for insurance.
     *
     * This should be one of the options from the Insurance source model
     * (Temando_Temando_Model_System_Config_Source_Insurance). These include
     * "disabled", "optional", and "mandatory". Depending on which setting is
     * active, the customer's choice of insurance will be restricted.
     */
    public function setSetting($value)
    {
        switch ($value) {
            case Temando_Temando_Model_System_Config_Source_Insurance::DISABLED:
                $this->setForcedValue(self::NO);
                break;
                
            case Temando_Temando_Model_System_Config_Source_Insurance::MANDATORY:
                $this->setForcedValue(self::YES);
                break;
                
            case Temando_Temando_Model_System_Config_Source_Insurance::OPTIONAL:
                $this->setForcedValue(null); // no forced value
                break;
        }
        
        return $this;
    }
    
    /**
     * Sets the markup amount.
     *
     * @param float $value
     */
    public function setMarkup($value)
    {
        $this->_action->setMarkup($value);
        return $this;
    }
    
    /**
     * Controls the way the markup amount (set with $this->setMarkup()) is
     * interpreted (either a fixed amount, or percentage).
     *
     * @param string $type
     */
    public function setMarkupType($type)
    {
        $this->_action->setMarkupType($type);
        return $this;
    }
    
    /**
     * The parent function applies the action depending on the value, here we
     * also update the quote's information to indicate that it includes
     * insurance.
     *
     * (non-PHPdoc)
     *
     * @see Temando_Temando_Model_Option_Boolean::_apply()
     */
    protected function _apply($value, &$quote)
    {
        /* @var $quote Temando_Temando_Model_Quote */
        if (parent::_apply($value, $quote)) {
            $quote->setInsuranceIncluded(true);
        }
    }
    
}
