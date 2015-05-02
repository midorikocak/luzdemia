<?php

class Temando_Temando_Model_Option_Footprints extends Temando_Temando_Model_Option_Boolean
{
    
    protected $_id = 'footprints';
    protected $_name = 'Include Charitable Micro donation';
    protected $_action_type = 'footprints';
    protected $_desc = "Include a $1 donation to charity in your transaction. The Footprints Network aggregates thousands of donations from online transactions to fund community development projects. Visit Footprintsnetwork.org to view projects.";
    
    /**
     * Sets the current "setting" for footprints.
     *
     * This should be one of the options from the footprints source model
     * (Temando_Temando_Model_System_Config_Source_Footprints). These include
     * "disabled", "optional", and "mandatory". Depending on which setting is
     * active, the customer's choice of footprints will be restricted.
     */
    public function setSetting($value)
    {
        switch ($value) {
            case Temando_Temando_Model_System_Config_Source_Footprints::DISABLED:
                $this->setForcedValue(self::NO);
                break;
                
            case Temando_Temando_Model_System_Config_Source_Footprints::MANDATORY:
                $this->setForcedValue(self::YES);
                break;
                
            case Temando_Temando_Model_System_Config_Source_Footprints::OPTIONAL:
                $this->setForcedValue(null); // no forced value
                break;
        }
        
        return $this;
    }
    
    /**
     * The parent function applies the action depending on the value, here we
     * also update the quote's information to indicate that it includes
     * the footprints.
     *
     * (non-PHPdoc)
     *
     * @see Temando_Temando_Model_Option_Boolean::_apply()
     */
    protected function _apply($value, &$quote)
    {
        /* @var $quote Temando_Temando_Model_Quote */
        if (parent::_apply($value, $quote)) {
            $quote->setFootprintsIncluded(true);
        }
    }
    
}
