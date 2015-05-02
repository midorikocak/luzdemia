<?php

class Temando_Temando_Model_Option_Action_Markup extends Temando_Temando_Model_Option_Action_Abstract
{
    
    /**
     * The markup amount.
     *
     * This can be either a percentage or fixed amount (controlled
     * by $this->_markup_type).
     *
     * @var float
     */
    protected $_markup = self::DEFAULT_MARKUP;
    
    /**
     * Controls the way the markup amount ($this->_markup) is interpreted
     * (either a fixed amount, or percentage).
     *
     * Shold be one of:
     *   - Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_FIXED    // == 'F'
     *   - Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_PERCENT  // == 'P'
     *
     * @var unknown_type
     */
    protected $_markup_type = self::DEFAULT_MARKUP_TYPE;
    
    
    /**
     * The default markup amount.
     *
     * @var float
     */
    const DEFAULT_MARKUP = 1;
    
    /**
     * The default markup type.
     *
     * @var string
     */
    const DEFAULT_MARKUP_TYPE = Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_PERCENT;
    
    /**
     * Sets the markup amount.
     *
     * This can be either a percentage or a fixed amount (controlled by
     * $this->setMarkupType()).
     *
     * @param unknown_type $type
     *
     * @return Temando_Temando_Model_Option_Action_Markup
     */
    public function setMarkup($amount)
    {
        if (is_numeric($amount)) {
            $this->_markup = $amount;
        }
        
        return $this;
    }
    
    /**
     * Gets the markup amount.
     *
     * @return float
     */
    public function getMarkup()
    {
        return (float) $this->_markup;
    }
    
    /**
     * Controls the way the markup amount (set with $this->setMarkup()) is
     * interpreted (either a fixed amount, or percentage).
     *
     * Shold be one of:
     *   - Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_FIXED    // == 'F'
     *   - Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_PERCENT  // == 'P'
     *
     * @param string $type
     *
     * @return Temando_Temando_Model_Option_Action_Markup
     */
    public function setMarkupType($type)
    {
        $valid = (
            $type === Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_FIXED ||
            $type === Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_PERCENT
        );
        
        if ($valid) {
            $this->_markup_type = $type;
        }
        
        return $this;
    }
    
    /**
     * Gets the markup type (either a fixed amount, or percentage).
     *
     * @return string
     */
    public function getMarkupType()
    {
        return $this->_markup_type;
    }
    
    public function apply(&$quote)
    {
        $price = $quote->getTotalPrice();
        
        switch ($this->getMarkupType()) {
            case Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_FIXED:
                $price += $this->getMarkup();
                break;
                
            case Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_PERCENT:
                $price *= 1 + ($this->getMarkup() / 100);
                break;
        }
        
        $quote->setTotalPrice($price);
    }
    
}
