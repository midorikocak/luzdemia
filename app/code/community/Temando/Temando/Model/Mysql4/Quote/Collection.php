<?php

class Temando_Temando_Model_Mysql4_Quote_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    
    protected $_options = null;
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/quote');
    }
    
    public function __clone()
    {
        $this->load();
        $new_items = array();
        foreach ($this->_items as $item) {
            $new_items[] = clone $item;
        }
        $this->_items = $new_items;
    }
    
    /**
     * Gets the fastest quote in the collection.
     *
     * Because quotes' ETAs are specified as a range (e.g. "1-4 days"), the
     * average of the range is used to make the comparison.
     *
     * @return Temando_Temando_Model_Quote
     */
    public function getFastest()
    {
        $fastest = null;
        foreach ($this->_items as $item) {
            $fastest = $this->_getFaster($item, $fastest);
        }
        return $fastest;
    }
    
    /**
     * Returns the faster of two quotes.
     *
     * Because quotes' ETAs are specified as a range (e.g. "1-4 days"), the
     * average of the range is used to make the comparison.
     *
     * If one is null, the other is returned. If both are the same average
     * speed, the first quote is returned.
     *
     * @param Temando_Temando_Model_Quote $a the first quote.
     * @param Temando_Temando_Model_Quote $b the second quote.
     *
     * @return Temando_Temando_Model_Quote
     */
    protected function _getFaster($a, $b)
    {
        // if one is null, return the other.
        if (is_null($a)) {
            return $b;
        }
        if (is_null($b)) {
            return $a;
        }
        
        // average ETA
        $a_eta = ($a->getEtaFrom() + $a->getEtaTo()) / 2;
        $b_eta = ($b->getEtaFrom() + $b->getEtaTo()) / 2;
        
        if ($a_eta != $b_eta) {
            // different speed, return faster
            return $a_eta <= $b_eta ? $a : $b;
        } else {
            // same speed, return cheaper
            return $this->_getCheaper($a, $b);
        }
    }
    
    /**
     * Gets the cheapest quote in the collection.
     *
     * @return Temando_Temando_Model_Quote
     */
    public function getCheapest()
    {
        $cheapest = null;
        foreach ($this->_items as $item) {
            $cheapest = $this->_getCheaper($item, $cheapest);
        }
        return $cheapest;
    }
    
    /**
     * Returns the cheaper of two quotes.
     *
     * If one is null, the other is returned. If both are the same price, the
     * first quote is returned.
     *
     * @param Temando_Temando_Model_Quote $a the first quote.
     * @param Temando_Temando_Model_Quote $b the second quote.
     *
     * @return Temando_Temando_Model_Quote
     */
    protected function _getCheaper($a, $b)
    {
        // if one is null, return the other (if both are null, null is returned).
        if (is_null($a)) {
            return $b;
        }
        if (is_null($b)) {
            return $a;
        }
        
        return $a->getTotalPrice() <= $b->getTotalPrice() ? $a : $b;
    }
    
    /**
     * Don't try to load the collection if specific items have been added.
     *
     * @see Varien_Data_Collection::addItem()
     */
    public function addItem(Varien_Object $item)
    {
        $this->_setIsLoaded();
        return parent::addItem($item);
    }
    
}
