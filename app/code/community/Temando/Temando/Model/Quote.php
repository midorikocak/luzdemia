<?php

/**
 * @method Temando_Temando_Model_Quote setGenerated()
 * @method Temando_Temando_Model_Quote setAccepted()
 * @method Temando_Temando_Model_Quote setTotalPrice()
 * @method Temando_Temando_Model_Quote setBasePrice()
 * @method Temando_Temando_Model_Quote setTax()
 * @method Temando_Temando_Model_Quote setCurrency()
 * @method Temando_Temando_Model_Quote setDeliveryMethod()
 * @method Temando_Temando_Model_Quote setUsingGeneralRail()
 * @method Temando_Temando_Model_Quote setUsingGeneralRoad()
 * @method Temando_Temando_Model_Quote setUsingGeneralSea()
 * @method Temando_Temando_Model_Quote setUsingExpressAir()
 * @method Temando_Temando_Model_Quote setUsingExpressRoad()
 * @method Temando_Temando_Model_Quote setEtaFrom()
 * @method Temando_Temando_Model_Quote setEtaTo()
 * @method Temando_Temando_Model_Quote setGuaranteedEta()
 * @method Temando_Temando_Model_Quote setLoaded()
 * @method Temando_Temando_Model_Quote setInsuranceIncluded()
 * @method Temando_Temando_Model_Quote setCarbonIncluded()
 * @method Temando_Temando_Model_Quote setFootprintsIncluded()
 * @method Temando_Temando_Model_Quote setFootprintsTotalPrice()
 *
 * @method string getGenerated()
 * @method string getAccepted()
 * @method string getTotalPrice()
 * @method string getBasePrice()
 * @method string getTax()
 * @method string getCurrency()
 * @method string getDeliveryMethod()
 * @method string getUsingGeneralRail()
 * @method string getUsingGeneralRoad()
 * @method string getUsingGeneralSea()
 * @method string getUsingExpressAir()
 * @method string getUsingExpressRoad()
 * @method string getEtaFrom()
 * @method string getEtaTo()
 * @method string getGuaranteedEta()
 * @method string getLoaded()
 * @method boolean getInsuranceIncluded()
 * @method boolean getCarbonIncluded()
 * @method boolean getFootprintsIncluded()
 * @method float getFootprintsTotalPrice()
 */
class Temando_Temando_Model_Quote extends Mage_Core_Model_Abstract
{
    
    protected $_carrier = null;
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/quote');
    }
    
    public function __clone()
    {
        $this->_carrier = clone $this->getCarrier();
    }
    
    /**
     * Sets the carrier providing this quote.
     *
     * @param <type> $carrier_id
     *
     * @return Temando_Temando_Model_Quote
     */
    public function setCarrier($carrier_id)
    {
        $carrier = Mage::getModel('temando/carrier')
            ->load($carrier_id);
            
        if ($carrier->getId() == $carrier_id) {
            // exists in the database
            $this->_carrier = $carrier;
            $this->setData('carrier_id', $carrier_id);
        }
        return $this;
    }
    
    /**
     * Gets the carrier providing this quote.
     *
     * @return Temando_Temando_Model_Carrier
     */
    public function getCarrier()
    {
        if (!$this->_carrier) {
            $this->setCarrier($this->getCarrierId());
        }
        return $this->_carrier;
    }
    
    /**
     * Loads values into this object from a
     *
     * @param stdClass $response the SOAP response directly from the Temando
     * API.
     */
    public function loadResponse(stdClass $response)
    {
        if ($response instanceof stdClass) {
            $carrier = Mage::getModel('temando/carrier')->load($response->carrier->id, 'carrier_id');
            /* @var $carrier Temando_Temando_Model_Carrier */
                
            $carrier
                ->setCarrierId(isset($response->carrier->id) ? $response->carrier->id : '')
                ->setCompanyName(isset($response->carrier->companyName) ? $response->carrier->companyName : '')
                ->setCompanyContact(isset($response->carrier->companyContact) ? $response->carrier->companyContact : '')
                ->setStreetAddress(isset($response->carrier->streetAddress) ? $response->carrier->streetAddress : '')
                ->setStreetSuburb(isset($response->carrier->streetSuburb) ? $response->carrier->streetSuburb : '')
                ->setStreetCity(isset($response->carrier->streetCity) ? $response->carrier->streetCity : '')
                ->setStreetState(isset($response->carrier->streetState) ? $response->carrier->streetState : '')
                ->setStreetPostcode(isset($response->carrier->streetCode) ? $response->carrier->streetCode : '')
                ->setStreetCountry(isset($response->carrier->streetCountry) ? $response->carrier->streetCountry : '')
                ->setPostalAddress(isset($response->carrier->postalAddress) ? $response->carrier->postalAddress : '')
                ->setPostalSuburb(isset($response->carrier->postalSuburb) ? $response->carrier->postalSuburb : '')
                ->setPostalCity(isset($response->carrier->postalCity) ? $response->carrier->postalCity : '')
                ->setPostalState(isset($response->carrier->postalState) ? $response->carrier->postalState : '')
                ->setPostalPostcode(isset($response->carrier->postalCode) ? $response->carrier->postalCode : '')
                ->setPostalCountry(isset($response->carrier->postalCountry) ? $response->carrier->postalCountry : '')
                ->setPhone(isset($response->carrier->phone1) ? $response->carrier->phone1 : '')
                ->setEmail(isset($response->carrier->email) ? $response->carrier->email : '')
                ->setWebsite(isset($response->carrier->website) ? $response->carrier->website : '')
                ->save();
            
            $extras = $response->extras->extra;
            if (!is_array($extras)) {
                $extras = array($extras);
            }
            
            $extras_array = array();
            
            foreach ($extras as $extra) {
                $extra_id = trim(strtolower($extra->summary));
                $extra_id = str_replace(' ', '', $extra_id);
                $extras_array[$extra_id] = array(
                    'summary'    => $extra->summary,
                    'details'    => $extra->details,
                    'totalPrice' => $extra->totalPrice,
                    'basePrice'  => $extra->basePrice,
                    'tax'        => $extra->tax,
                );
            }
            
            $this
                ->setCarrier($carrier->getId())
                ->setAccepted($response->accepted == 'Y')
                ->setTotalPrice($response->totalPrice)
                ->setBasePrice($response->basePrice)
                ->setTax($response->tax)
                ->setCurrency($response->currency)
                ->setDeliveryMethod($response->deliveryMethod)
                ->setEtaFrom($response->etaFrom)
                ->setEtaTo($response->etaTo)
                ->setGuaranteedEta($response->guaranteedEta == 'Y')
                ->setExtras($extras_array)
                ->setCarbonTotalPrice(array_key_exists('carbonoffset', $extras_array) ? $extras_array['carbonoffset']['totalPrice'] : 0)
                ->setInsuranceTotalPrice(array_key_exists('insurance', $extras_array) ? $extras_array['insurance']['totalPrice'] : 0)
		->setFootprintsTotalPrice(array_key_exists('footprints', $extras_array) ? $extras_array['footprints']['totalPrice'] : 0)
                ->setLoaded(true);
        }
        return $this;
    }
    
    public function toBookingRequestArray($options)
    {
        $extras = $this->getExtras();
        
        if (isset($options['insurance']) && ($options['insurance'] === 'Y')) {
            $insurance = $extras['insurance'];
        } else {
            $insurance = false;
        }
        
        if (isset($options['carbonoffset']) && ($options['carbonoffset'] === 'Y')) {
            $carbon = $extras['carbonoffset'];
        } else {
            $carbon = false;
        }
	
	if (isset($options['footprints']) && ($options['footprints'] === 'Y')) {
            $footprints = $extras['footprints'];
        } else {
            $footprints = false;
        }
        
        $request = array(
            'totalPrice'     => $this->getTotalPrice(),
            'basePrice'      => $this->getBasePrice(),
            'tax'            => $this->getTax(),
            'currency'       => $this->getCurrency(),
            'deliveryMethod' => $this->getDeliveryMethod(),
            'etaFrom'        => $this->getEtaFrom(),
            'etaTo'          => $this->getEtaTo(),
            'guaranteedEta'  => $this->getGuaranteedEta() ? 'Y' : 'N',
            'carrierId'      => $this->getCarrier()->getCarrierId(),
        );
        
        if ($carbon || $insurance || $footprints) {
            $request['extras'] = array();
            $request['extras']['extra'] = array();
        }
        
        if ($carbon) {
            $request['extras']['extra'][] = $carbon;
        }
        if ($insurance) {
            $request['extras']['extra'][] = $insurance;
        }
	if ($footprints) {
	    $request['extras']['extra'][] = $footprints;
	}
        
        return $request;
    }
    
    public function setExtras($extras)
    {
        $this->setData('extras', serialize($extras));
        return $this;
    }
    
    public function getExtras()
    {
        if ($this->getData('extras')) {
            return unserialize($this->getData('extras'));
        }
        return null;
    }
    
    public function getDescription($showMethod = true, $showEta = true)
    {
        $title = '';    
        $title .= $this->getCarrier()->getCompanyName();
	
	if ($showMethod && $showEta) {
	    $title .= ' - ' . $this->getDeliveryMethod(). ' [' . $this->getEtaDescription() . ']';
	} else if ($showMethod) {
	    $title .= ' - ' . $this->getDeliveryMethod();
	} else if ($showEta) {
	    $title .= ' [' . $this->getEtaDescription(). ']';
	}
        
        return $title . ' ' . $this->getExtraTitle();;
    }
    
    
    public function getTotalPriceIncSelectedExtras()
    {
	$price = $this->getTotalPrice();
	if($this->getInsuranceIncluded()) {
	    $price += $this->getInsuranceTotalPrice();
	}
	if($this->getCarbonIncluded()) {
	    $price += $this->getCarbonTotalPrice();
	}
	if($this->getFootprintsIncluded()) {
	    $price += $this->getFootprintsTotalPrice();
	}
	
	return $price;
	
    }

    public function getExtraTitle()
    {
        $title = '';
        if ($this->getInsuranceIncluded()) {
            $title .= Mage::helper('temando')->__(', includes insurance');
        }
        if ($this->getCarbonIncluded()) {
            if ($this->getInsuranceIncluded()) {
                $title .= Mage::helper('temando')->__(' and ');
            } else {
                $title .= Mage::helper('temando')->__(', includes ');
            }
            $title .= Mage::helper('temando')->__(' carbon offset');
        }
	if ($this->getFootprintsIncluded()) {
            if ($this->getInsuranceIncluded() || $this->getCarbonIncluded()) {
                $title .= Mage::helper('temando')->__(' and ');
            } else {
                $title .= Mage::helper('temando')->__(', includes ');
            }
            $title .= Mage::helper('temando')->__(' footprints');
        }

        return $title;
    }
    
    public function getEtaDescription()
    {
        $title = $this->getEtaFrom();
        
        if ($this->getEtaFrom() != $this->getEtaTo()) {
            $title .= ' - ' . $this->getEtaTo();
        }
        
        $title .= Mage::helper('temando')->__(' day');
        
        if ($this->getEtaTo() > 1) {
            $title .= Mage::helper('temando')->__('s');
        }
        
        return $title;
    }
    
    /**
     * Returns the average ETA.
     *
     * e.g. for an ETA of 1-4 days, the average is 2.5 days.
     */
    public function getAverageEta()
    {
        return ($this->getEtaFrom() + $this->getEtaTo()) / 2;
    }

}
