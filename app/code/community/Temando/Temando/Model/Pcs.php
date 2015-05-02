<?php
/**
 * @method Temando_Temando_Model_Pcs setCountry() 
 * @method Temando_Temando_Model_Pcs setQuery() 
 * 
 * @method string getCountry()
 * @method string getQuery()
 */

class Temando_Temando_Model_Pcs extends Mage_Core_Model_Abstract
{
    /**
     * Address validation Temando AVS URL 
     */
    const AVS_URL = "http://avs.temando.com/avs/search/country/%s/%s.json?limit=1000";
    
    /**
     * The HTTP Client
     * @var Varien_Http_Client
     */
    protected $_client = null;
    
    /**
     * Default country (only one country in allowed countries)
     * @var string
     */
    protected $_defaultCountry = null;
    
    
    public function _construct()
    {
        parent::_construct();
	$this->_prepareClient()->_loadDefaultCountry();
    }
    
    /**
     * Returns address postcode/country combinations as an array.
     * Empty array is returned if no suggestions are found.
     * 
     * @return array 
     */
    public function getSuggestions()
    {
	if(!$this->_validate()) {
	    return array();
	}
	
	$url = sprintf(self::AVS_URL, strtoupper($this->getCountry()), rawurlencode($this->getQuery()));
	try {
	    $this->_client->setUri($url);
            $rawBody = $this->_client->request(Varien_Http_Client::GET)->getRawBody();
            return Mage::helper('core')->jsonDecode($rawBody, true);
	} catch (Exception $e) {
	    Mage::log($e->getMessage(), null, 'temando.log', true);
	    return array();
	}
    }
    
    /**
     * Checks current request - country & query
     * 
     * @return boolean 
     */
    protected function _validate()
    {
	if (strlen(trim($this->getCountry())) === 0 && $this->_defaultCountry) {
            $this->setCountry($this->_defaultCountry);
        }
	return 	strlen(trim($this->getCountry())) > 0 &&
		strlen(trim($this->getQuery())) > 0;
	
    }
    
    /**
     * Initializes http client to communicate with AVS service
     * 
     * @return \Temando_Temando_Model_Pcs 
     */
    protected function _prepareClient()
    {
	if(!$this->_client) {
	    $this->_client = new Varien_Http_Client();
	    $this->_client->setConfig(array('maxredirects' => 0, 'timeout' => 15));
	}
	return $this;
    }
    
    /**
     * Loads default destination country 
     * 
     * @return \Temando_Temando_Model_Pcs
     */
    protected function _loadDefaultCountry()
    {
        if(is_null($this->_defaultCountry)) {
            $allowed = Mage::helper('temando')->getAllowedCountries();
            if (count($allowed) === 1) {
                //one allowed country - load as default
                reset($allowed);
                $this->_defaultCountry = key($allowed);
            }
        }
        return $this;
    }
    
}
