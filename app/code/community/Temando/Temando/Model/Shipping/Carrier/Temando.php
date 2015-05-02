<?php
/**
 * @method Temando_Temando_Model_Shipping_Carrier_Temando setIsProductPage(boolean $flag)
 * @method Temando_Temando_Model_Shipping_Carrier_Temando setIsCartPage(boolean $flag)
 * 
 * @method boolean getIsProductPage()
 * @method boolean getIsCartPage()
 */


class Temando_Temando_Model_Shipping_Carrier_Temando 
    extends Mage_Shipping_Model_Carrier_Abstract 
	implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * Error Constants
     */
    const ERR_INVALID_COUNTRY = 'To and From addresses must be within Australia';
    const ERR_INVALID_DEST    = 'Please enter a delivery address to view available shipping methods';
    const ERR_NO_METHODS      = 'No shipping methods available';
    const ERR_INTERNATIONAL   = 'International delivery is not available at this time.';
    
    /**
     * Carrier's code
     */
    const CARRIER_CODE = 'temando';
    
    /**
     * Error Map
     * 
     * @var array 
     */
    protected static $_errors_map = array(
        "The 'destinationCountry', 'destinationCode' and 'destinationSuburb' elements (within the 'Anywhere' type) do not contain valid values.  These values must match with the predefined settings in the Temando system."
                => "Invalid suburb / postcode combination."
    );
    
    /**
     * Carrier's code
     *
     * @var string
     */
    protected $_code = 'temando';
    
    /**
     * Carrier's title
     *
     * @var string
     */
    protected $_title = 'Temando';

    /**
     * Rates result
     *
     * @var array|null
     */
    protected $_rates;
    
    /**
     * @var Mage_Shipping_Model_Rate_Request
     */
    protected $_rate_request;
    
    /**
     * Current pricing method as set in Temando Settings
     * 
     * @var string 
     */
    protected $_pricing_method;

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return boolean
     */
    public function isTrackingAvailable()
    {
        return true;
    }
    
    /**
     * Check if carrier has shipping label option available
     *
     * @return boolean
     */
    public function isShippingLabelsAvailable()
    {
        return false;
    }
    
    
    public function __construct()
    {
        parent::__construct();
        $this->_pricing_method = $this->getConfigData('pricing/method');
        $this->setIsProductPage(("etemando" == Mage::app()->getRequest()->getModuleName()) && ("pcs" == Mage::app()->getRequest()->getControllerName()));
	$this->setIsCartPage(("checkout" == Mage::app()->getRequest()->getModuleName()) && ("cart" == Mage::app()->getRequest()->getControllerName()));
    }
    
    /**
     * Checks if the to address is within allowed countries
     *
     * @return boolean
     */
    protected function _canShip(Mage_Shipping_Model_Rate_Request $request)
    {
        return array_key_exists($request->getDestCountryId(), Mage::helper('temando')->getAllowedCountries());
    }
    
    /**
     * Creates a rate method based on a Temando API quote.
     *
     * @param Mage_Shipping_Model_Rate_Result_Method the quote from the
     * Temando API.
     *
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    protected function _getRateFromQuote($quote, $method_id)
    {
        $price = $this->getFinalPriceWithHandlingFee($quote->getTotalPrice());
        $title = $quote->getDescription($this->getConfigData('options/show_transit_type'), $this->getConfigData('options/show_transit_time'));

        $method = Mage::getModel('shipping/rate_result_method')
            ->setCarrier($this->_code)
            ->setCarrierTitle($this->_title)
            ->setMethodTitle($title)
            ->setMethod($method_id)
            ->setPrice($price)
            ->setCost($quote->getTotalPrice());
        
        return $method;
    }
    
    /**
     * Creates the flat rate method, with the price set in the config. An
     * optional parameter allows the price to be overridden.
     *
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    protected function _getFlatRateMethod($price = false, $free = false)
    {   
        if ($price === false) {
            $cost = $this->getConfigData('pricing/shipping_fee');
	    $price = $this->getFinalPriceWithHandlingFee($cost);
        } else {
            $cost = $price;
        }

        $title = $free ? Mage::helper('temando')->__('Free Shipping') : Mage::helper('temando')->__('Flat Rate');
        $method = Mage::getModel('shipping/rate_result_method')
            ->setCarrier($this->_code)
            ->setCarrierTitle($this->_title)
            ->setMethodTitle($title)
            ->setMethod($free ? Temando_Temando_Model_Carrier::FREE : Temando_Temando_Model_Carrier::FLAT_RATE)
            ->setPrice($price)
            ->setCost($cost);
            
        return $method;
    }

    /**
     * Returns shipping rate result error method 
     * 
     * @param string $errorText
     * @return Mage_Shipping_Model_Rate_Result_Error
     */
    protected function _getErrorMethod($errorText)
    {
        $error = Mage::getModel('shipping/rate_result_error');
        $error->setCarrier($this->_code);
        $error->setCarrierTitle($this->_title);
        if (isset(self::$_errors_map[$errorText])) {
            $errorText = self::$_errors_map[$errorText];
        }
        $error->setErrorMessage($errorText);

        return $error;
    }
    
    /**
     * Creates a string describing the applicable elements of a rate request.
     *
     * This is used to determine if the quotes fetched last time should be
     * refreshed, or if they can remain valid.
     *
     * @param Mage_Shipping_Model_Rate_Request $rate_request
     *
     * @return boolean
     */
    protected function _createRequestString(Mage_Shipping_Model_Rate_Request $request, $salesQuoteId)
    {
        $requestString = $salesQuoteId . '|';
        foreach ($request->getAllItems() as $item) {
            $requestString .= $item->getProductId() . 'x' . $item->getQty();
        }
        
        $requestString .= '|' . $request->getDestCity();
        $requestString .= '|' . $request->getDestCountryId();
        $requestString .= '|' . $request->getDestPostcode();
       
        return $requestString;
    }
    
    /**
     * Returns available shipping methods for current request based on pricing method
     * specified in Temando Settings
     * 
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result|Mage_Shipping_Model_Rate_Result_Error
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
	$result = Mage::getModel('shipping/rate_result');
	/* @var $result Mage_Shipping_Model_Rate_Result */
	
	//check origin/destination country
        if (!$this->_canShip($request)) { 
	    return $this->_getErrorMethod(self::ERR_INVALID_COUNTRY);
	}

	//OneStepCheckout inserts '-' in city/pcode if no default configured
        if (!$request->getDestCountryId() || !$request->getDestPostcode() || !$request->getDestCity() ||
		$request->getDestPostcode() == '-' || $request->getDestCity() == '-') {
	    return $this->_getErrorMethod(self::ERR_INVALID_DEST);
        }
	
	//check if eligible for free shipping
        if ($this->isFreeShipping($request)) {
            $result->append($this->_getFlatRateMethod('0.00', true));
	    return $result;
        }

	//check for flat rate
        if ($this->_pricing_method == Temando_Temando_Model_System_Config_Source_Pricing::FLAT_RATE) {
            $result->append($this->_getFlatRateMethod());
	    return $result;
        }

	//prepare extras
        $insurance = Mage::getModel('temando/option_insurance')->setSetting(Mage::getStoreConfig('temando/insurance/status'));
        $carbon = Mage::getModel('temando/option_carbonoffset')->setSetting(Mage::getStoreConfig('temando/carbon/status'));
	$footprints = Mage::getModel('temando/option_footprints')->setSetting(Mage::getStoreConfig('temando/footprints/status'));
        
        if ($this->getIsProductPage() || $this->getIsCartPage()) 
	{
            if (!in_array($insurance->getForcedValue(), array(Temando_Temando_Model_Option_Boolean::YES, Temando_Temando_Model_Option_Boolean::NO))) {
                $insurance->setForcedValue(Temando_Temando_Model_Option_Boolean::NO);
            }

            if (!in_array($carbon->getForcedValue(), array(Temando_Temando_Model_Option_Boolean::YES, Temando_Temando_Model_Option_Boolean::NO))) {
                $carbon->setForcedValue(Temando_Temando_Model_Option_Boolean::NO);
            }
	    
	    if (!in_array($footprints->getForcedValue(), array(Temando_Temando_Model_Option_Boolean::YES, Temando_Temando_Model_Option_Boolean::NO))) {
                $footprints->setForcedValue(Temando_Temando_Model_Option_Boolean::NO);
            }
        }
        /* @var Temando_Temando_Model_Options $options */
        $options = Mage::getModel('temando/options')->addItem($insurance)->addItem($carbon)->addItem($footprints);

	//get magento sales quote id
	$salesQuoteId = Mage::getSingleton('checkout/session')->getQuoteId();
	if (!$salesQuoteId && Mage::app()->getStore()->isAdmin()) {
	    $salesQuoteId = Mage::getSingleton('adminhtml/session_quote')->getQuote()->getId();
	}
	if (!$salesQuoteId && $this->getIsProductPage()) {
	    $salesQuoteId = 100000000 + mt_rand(0, 100000);
	}

	//save current extras
        if (is_null(Mage::registry('temando_current_options'))) {
            Mage::register('temando_current_options', $options);
        }

	//get available shipping methods (quotes from the API)
	//check if request same as previous
	$lastRequest = Mage::getSingleton('checkout/session')->getTemandoRequestString();
        if ($lastRequest == $this->_createRequestString($request, $salesQuoteId)) 
	{
            //request is the same as previous, load existing quotes from DB
            $quotes = Mage::getModel('temando/quote')->getCollection()
		    ->addFieldToFilter('magento_quote_id', $salesQuoteId)
		    ->getItems();
        } 
	else 
	{  
            try {
		$apiRequest = Mage::getModel('temando/api_request');		    
		$apiRequest
                    ->setUsername($this->getConfigData('general/username'))
                    ->setPassword($this->getConfigData('general/password'))
                    ->setSandbox($this->getConfigData('general/sandbox'))
                    ->setMagentoQuoteId($salesQuoteId)
                    ->setDestination(
                        $request->getDestCountryId(),
                        $request->getDestPostcode(),
                        $request->getDestCity(),
                        $request->getDestStreet())
                    ->setItems($request->getAllItems())
		    ->setReady(Mage::helper('temando')->getReadyDate())
		    ->setAllowedCarriers($this->getAllowedMethods());
                 
                $coll = $apiRequest->getQuotes();
		if ($coll instanceof Temando_Temando_Model_Mysql4_Quote_Collection) {
		    $quotes = $coll->getItems();
		}
            } catch (Exception $e) {
                switch(Mage::helper('temando')->getConfigData('pricing/error_process')) {
		    case Temando_Temando_Model_System_Config_Source_Errorprocess::VIEW:
			return $this->_getErrorMethod($e->getMessage());
			break;
		    case Temando_Temando_Model_System_Config_Source_Errorprocess::FLAT:
			$result->append($this->_getFlatRateMethod());
			return $result;
			break;
		}
            }
        }

	//process filters and apply extras
	if(empty($quotes)) {
	    return $this->_getErrorMethod(self::ERR_NO_METHODS);
	} else {
	    switch($this->_pricing_method) {
		case Temando_Temando_Model_System_Config_Source_Pricing::DYNAMIC_CHEAPEST:
		    $quotes = Mage::helper('temando/functions')->getCheapestQuote($quotes);
		    break;
		case Temando_Temando_Model_System_Config_Source_Pricing::DYNAMIC_FASTEST:
		    $quotes = Mage::helper('temando/functions')->getFastestQuote($quotes);
		    break;
		case Temando_Temando_Model_System_Config_Source_Pricing::DYNAMIC_FASTEST_AND_CHEAPEST:
		    $quotes = Mage::helper('temando/functions')->getCheapestAndFastestQuotes($quotes);
		    break;
	    }
	    if(!is_array($quotes)) { $quotes = array($quotes); }
	    foreach($quotes as $id => $quote)
	    {
		$permutations = $options->applyAll($quote);
		foreach($permutations as $permId => $permutation) {
		    $result->append($this->_getRateFromQuote($permutation, $quote->getId() . '_' . $permId));
		}
	    }

	}

        Mage::getSingleton('checkout/session')->setTemandoRequestString($this->_createRequestString($request, $salesQuoteId));
        return $result;
    }
    
    /**
     * Returns true if request is elegible for free shipping, false otherwise
     * 
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return boolean
     */
    public function isFreeShipping($request)
    {
	//check pricing method first
	if($this->_pricing_method == Temando_Temando_Model_System_Config_Source_Pricing::FREE) {
	    return true;
	}
	
	//check if all items have free shipping or free shipping over amount enabled and valid for this request
	$allItemsFree = true; $total = 0;
        foreach ($request->getAllItems() as $item) {
	    /* @var $item Mage_Sales_Model_Quote_Item */
            if ($item->getProduct()->isVirtual() || $item->getParentItem()) { continue; }
            if ($item->getFreeShipping()) { continue; }
	    
	    $value = $item->getValue();
            if (!$value) { $value = $item->getRowTotalInclTax(); }
	    if (!$value) { $value = $item->getRowTotal(); }	    
            if (!$value) {
                $qty = $item->getQty() ? $item->getQty() : $item->getQtyOrdered();
                $value = $item->getPrice() * $qty;
            }
	    $total += $value;
	    //not all items with free shipping if here
            $allItemsFree = false;
        }
	
	if($allItemsFree ||
		($this->getConfigData('free_shipping_enable') && $total >= $this->getConfigData('free_shipping_subtotal'))) {
	     return true;
	}
	
	return false;
    }

    /**
     * Return list of allowed carriers
     * 
     * @return array
     */
    public function getAllowedMethods()
    {
        return explode(',', Mage::getStoreConfig('carriers/temando/allowed_methods'));
    }

    public function getTrackingInfo($tracking_number)
    {
        $api = Mage::getModel('temando/api_client');
        $api->connect(
            Mage::helper('temando')->getConfigData('general/username'),
            Mage::helper('temando')->getConfigData('general/password'),
            Mage::helper('temando')->getConfigData('general/sandbox'));

        $_t = explode('Request Id: ', $tracking_number);
        if (isset($_t[1])) {
            $tracking_number = $_t[1];
        }

        $status = $api->getRequest(array('requestId' => $tracking_number));
        
        $result = Mage::getModel('shipping/tracking_result_abstract')
            ->setTracking($tracking_number);
        /* @var $result Mage_Shipping_Model_Tracking_Result_Abstract */
        if ($status && $status->request->quotes && $status->request->quotes->quote) {
            if (isset($status->request->quotes->quote->carrier->companyName)) {
                $result->setCarrierTitle($status->request->quotes->quote->carrier->companyName);
            }

            if (isset($status->request->quotes->quote->trackingStatus)) {
                $result->setStatus($status->request->quotes->quote->trackingStatus);
            } else {
                $result->setStatus(Mage::helper('temando')->__('Unavailable'));
            }
            
            $text = '';
            if (isset($status->request->quotes->quote->trackingFurtherDetails)) {
                $text .= $status->request->quotes->quote->trackingFurtherDetails;
            }
            if (isset($status->request->quotes->quote->trackingLastChecked)) {
                $text .= 'Last Update: ' . date('Y-m-d h:ia', strtotime($status->request->quotes->quote->trackingLastChecked));
            }
            
            if ($text) {
                $result->setTrackSummary($text);
            }
        } else {
            $result->setErrorMessage(Mage::helper('temando')->__('An error occurred while fetching the shipment status.'));
        }
        
        return $result;
    }
    
    public function getConfigData($field)
    {
        if (in_array($field, array('handling_fee', 'handling_type'))) {
            $field = 'pricing/' . $field;
        }

        $parent = parent::getConfigData($field);
        return $parent !== null ? $parent : Mage::helper('temando')->getConfigData($field);
    }
    
    /**
     * Returns Temando carrier code
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }
    
    /**
     * Returns Temando carrier title
     * 
     * @return string
     */
    public function getTitle()
    {
	return $this->_title;
    }

    /**
     * Is state province required
     *
     * @return bool
     */
    public function isStateProvinceRequired()
    {
        return false;
    }

    /**
     * Check if city option required
     *
     * @return boolean
     */
    public function isCityRequired()
    {
        return true;
    }

    /**
     * Determine whether zip-code is required for the country of destination
     *
     * @param string|null $countryId
     * @return bool
     */
    public function isZipCodeRequired($countryId = null)
    {
        return true;
    }
    
}
