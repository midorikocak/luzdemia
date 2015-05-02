<?php

class Temando_Temando_Model_Option_Carbonoffset extends Temando_Temando_Model_Option_Boolean
{
    
    protected $_id = 'carbonoffset';
    protected $_name = 'Include Carbon Offset';
    protected $_action_type = 'carbon';
    protected $_desc = "The Gaia Partnership's CO2counter calculates the carbon emissions that are attributed to your transport needs. If you do not wish to offset the carbon emissions from your transport service please unselect the tick box above.";
    
    /**
     * Sets the current "setting" for carbon offsets.
     *
     * This should be one of the options from the carbon offset source model
     * (Temando_Temando_Model_System_Config_Source_Carbon). These include
     * "disabled", "optional", and "mandatory". Depending on which setting is
     * active, the customer's choice of carbon offsets will be restricted.
     */
    public function setSetting($value)
    {
        switch ($value) {
            case Temando_Temando_Model_System_Config_Source_Carbon::DISABLED:
                $this->setForcedValue(self::NO);
                break;
                
            case Temando_Temando_Model_System_Config_Source_Carbon::MANDATORY:
                $this->setForcedValue(self::YES);
                break;
                
            case Temando_Temando_Model_System_Config_Source_Carbon::OPTIONAL:
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
     * the carbon offset.
     *
     * (non-PHPdoc)
     *
     * @see Temando_Temando_Model_Option_Boolean::_apply()
     */
    protected function _apply($value, &$quote)
    {
        /* @var $quote Temando_Temando_Model_Quote */
        if (parent::_apply($value, $quote)) {
            $quote->setCarbonIncluded(true);
        }
    }
    
}
