<?php

class Temando_Temando_Model_Api_Request extends Mage_Core_Model_Abstract
{ 
    /**
     * @var Temando_Temando_Model_Api_Request_Anythings
     */
    protected $_anythings = null;
    
    /**
     * @var Temando_Temando_Model_Api_Request_Anywhere
     */
    protected $_anywhere = null;
    
    /**
     * @var Temando_Temando_Model_Api_Request_Anytime
     */
    protected $_anytime = null;
    
    /**
     * @var array
     */
    protected $_quotes = null;

    /**
     * If request includes anytime component
     * 
     * @var boolean 
     */
    protected $use_anytime = false;
       
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/api_request');
        
        $this->_anythings = Mage::getModel('temando/api_request_anythings');
        $this->_anywhere = Mage::getModel('temando/api_request_anywhere');
        $this->_anytime = Mage::getModel('temando/api_request_anytime');
    }
    
    public function setItems($items)
    {
        $this->_anythings->setItems($items);
        return $this;
    }
    
    public function setDestination($country, $postcode, $city, $street = null)
    {
        $this->_anywhere
            ->setDestinationCountry($country)
            ->setDestinationPostcode($postcode)
            ->setDestinationCity($city)
            ->setDestinationStreet($street);
        return $this;
    }
    
    public function setReady($timestamp = null, $time_of_day = 'AM')
    {
        if (!is_null($timestamp)) {
            $this->use_anytime = true;
        }

        $this->_anytime
            ->setReadyDate($timestamp)
            ->setReadyTimeOfDay($time_of_day);
        return $this;
    }
    
    /**
     * Gets all available Temando quotes for this request.
     *
     * @return Temando_Temando_Model_Mysql4_Quote_Collection
     */
    public function getQuotes()
    {
        if (!$this->_fetchQuotes()) {
            // validation failed
            return false;
        }
        
        $quotes = Mage::getModel('temando/quote')->getCollection()
            ->addFieldToFilter('magento_quote_id', $this->getMagentoQuoteId());
        
        return $quotes;
    }
    
    /**
     * Fetches the quotes and saves them into the database.
     *
     * @throws Exception
     */
    protected function _fetchQuotes()
    {
        $request = $this->toRequestArray();

        if (!$request) {
            return false;
        }

        try {
            $api = Mage::getModel('temando/api_client')->connect(
			$this->getUsername(), $this->getPassword(), $this->getSandbox()
	    );
            $quotes = $api->getQuotes($request);           
        } catch(Exception $e) {
            throw $e;
        }
        
        // filter by allowed carriers, if the filter has been set
        $filtered_quotes = $quotes;
        if (is_array($this->getAllowedCarriers())) {
            $filtered_quotes = array();
            foreach ($quotes as $quote) {
                /* @var $quote Temando_Temando_Model_Quote */
                $quote_carrier_id = $quote->getCarrier()->getCarrierId();
                if (in_array($quote_carrier_id, $this->getAllowedCarriers())) {
                    $filtered_quotes[] = $quote;
                }
            }
        }
        
        $this->_saveQuotes($filtered_quotes);
        
        return true;
    }
    
    /**
     * Saves an array of quotes to the database.
     *
     * @param array $quotes an array of Temando_Temando_Model_Quote objects.
     */
    protected function _saveQuotes($quotes)
    {
        // delete all old Temando quotes for this Magento quote
        $old_quotes = Mage::getModel('temando/quote')->getCollection()
            ->addFieldToFilter('magento_quote_id', $this->getMagentoQuoteId());
        foreach ($old_quotes as $quote) {
            /* @var $quote Temando_Temando_Model_Quote */
            $quote->delete();
        }
        
        // add new Temando quotes to the database
        foreach ($quotes as $quote) {
            $quote->setMagentoQuoteId($this->getMagentoQuoteId())
                ->save();
        }
        
        return $this;
    }
    
    
    public function toRequestArray()
    {
        if (!$this->validate()) {
            return false;
        }
        
        $goodsValue = $this->_anythings->getGoodsValue();        
        $return = array(
            'anythings' => $this->_anythings->toRequestArray(),
            'anywhere' => $this->_anywhere->toRequestArray(),
        );

        if ($goodsValue) {
            $return['general'] = array(
                'goodsValue' => round($goodsValue, 2),
            );
        }

        if ($this->use_anytime) {
            $return['anytime'] = $this->_anytime->toRequestArray();
        }

        return $return;
    }
    
    public function validate()
    {
        return
            $this->getMagentoQuoteId() &&
            $this->_anythings instanceof Temando_Temando_Model_Api_Request_Anythings &&
            $this->_anywhere instanceof Temando_Temando_Model_Api_Request_Anywhere &&
            $this->_anytime instanceof Temando_Temando_Model_Api_Request_Anytime &&
            $this->_anythings->validate() &&
            $this->_anywhere->validate() &&
            $this->_anytime->validate();
    }
    
}
