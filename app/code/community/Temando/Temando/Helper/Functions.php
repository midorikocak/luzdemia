<?php
/**
 * Description of Functions
 *
 * @author martin
 */
class Temando_Temando_Helper_Functions extends Mage_Core_Helper_Abstract {

    public function getFastestQuote($quotes)
    {
        $fastest = null;
        foreach ($quotes as $quote) {
            $fastest = $this->_getFaster($quote, $fastest);
        }
        return $fastest;
    }
    
    protected function  _getFaster($a, $b)
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
            return self::_getCheaper($a, $b);
        }
    }
    
    public function getCheapestQuote($quotes)
    {
        $cheapest = null;
        foreach ($quotes as $quote) {
            $cheapest = $this->_getCheaper($quote, $cheapest);
        }
        return $cheapest;
    }
    
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
    
    public function getCheapestAndFastestQuotes($quotes)
    {
	$cheapest = $this->getCheapestQuote($quotes);
	$fastest = $this->getFastestQuote($quotes);
	
	if($cheapest->getId() === $fastest->getId())
	    return array($cheapest);
	
	return array($cheapest, $fastest);
    }
    
}


