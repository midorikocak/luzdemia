<?php

class Temando_Temando_Helper_Data extends Mage_Core_Helper_Abstract {

    const DEFAULT_WAREHOUSE_NAME = 'Magento Warehouse';
    
    private $_temandoAttributes = array(
	'temando_packaging_mode',
	'temando_packaging',
	'temando_fragile',
	'temando_length',
	'temando_width',
	'temando_height'
    );

    protected $_allowedCountries = array(
	'AU' => 'Australia',
    );

    /**
     * Retrieves an element from the module configuration data.
     *
     * @param string $field
     */
    public function getConfigData($field) {
	$path = 'temando/' . $field;
	return Mage::getStoreConfig($path);
    }

    /**
     * Returns array of allowed countries based on Magento system configuration
     * and Temando plugin allowed countries.
     * 
     * @param boolean $asJson
     * @return array
     */
    public function getAllowedCountries($asJson = false) {
	$specific = Mage::getStoreConfig('carriers/temando/sallowspecific');
	//check if all allowed and return selected
	if($specific == 1) {
	    $availableCountries = explode(',', Mage::getStoreConfig('carriers/temando/specificcountry'));
	    $countries = array_intersect_key($this->_allowedCountries, array_flip($availableCountries));
	    if($asJson) {
		return Mage::helper('core')->jsonEncode($countries);
	    } else {
		return $countries;
	    }    
	}
	//return all allowed
	if($asJson) {
	    return Mage::helper('core')->jsonEncode($this->_allowedCountries);
	} else {
	    return $this->_allowedCountries;	
	}
    }
    
    /**
     * Applies Temando attributes to sales quote item object
     * 
     * @param Mage_Sales_Model_Order_Item $item
     */
    public function applyTemandoParamsToItem($item)
    {
	$attribs = $this->getTemandoProductAttributes($item);
	foreach($attribs as $key => $val) {
	    $item->setData($key, $val);
	}
    }
    
    /**
     * Returns values for Temando specific product attributes. Currently handles simple
     * and configurable products only. Default attribute values from configuration are
     * returned if product temando retrieval mode is set to 'Use Defaults'
     * 
     * @param Mage_Sales_Model_Order_Item $item
     * @param Mage_Catalog_Model_Product $product
     * @return array Temando specific product attributes
     */
    public function getTemandoProductAttributes($item = null, $product = null)
    {
	$attribs = array();
	if($item) {
	    //Mage_Sales_Model_Order_Item = need to check if configurable
	    $product = Mage::getModel('catalog/product')->load($item->getProductId());
	    if ($product->isConfigurable()) {
		//configurable product - get selected simple product
		$simple_product = $this->getSelectedSimpleProductFromConfigurable($item);
		if($simple_product->getId() && $simple_product->getTemandoPackagingMode()) {
		    //simple product found and retrieval mode is set to 'As Defined' => Retrieve attributes
		    foreach($this->_temandoAttributes as $attribname) {
			$attribs[$attribname] = $simple_product->getData($attribname);
		    }
		} else if($simple_product->getId() && !$simple_product->getTemandoPackagingMode()) {
		    //simple product found and retrieval mode set to 'Use Defaults'
		    //try to take from configurable parent product before looking at defaults
		    if($product->getTemandoPackagingMode()) {
			foreach($this->_temandoAttributes as $attribname) {
			    $attribs[$attribname] = $product->getData($attribname);
			}
		    } else {
			//simple and parent-configurable set to 'Use Defaults'
			$attribs = $this->getDefaultTemandoAttributes();
		    }
		} //attribs array could be empty if there's an error retrieving simple product from configurable
		//apply simple product weight to parent configurable
		if ($simple_product->getId()) {
		    $item->setWeight($simple_product->getWeight());
		}
	    } else {
		//assuming simple product here ... possible bug if other product type used
		if($product->getTemandoPackagingMode()) {
		    //retrieval mode set to 'As Defined' => Retrieve attributes
		    foreach($this->_temandoAttributes as $attribname) {
			$attribs[$attribname] = $product->getData($attribname);
		    }
		} else {
		    $attribs = $this->getDefaultTemandoAttributes();
		}
	    }
	} else {
	    //Request from within admin panel direct on product object
	    if($product instanceof Mage_Catalog_Model_Product) {
		foreach($this->_temandoAttributes as $attribname) {
		    $attribs[$attribname] = $product->getData($attribname);
		}
	    }
	}
	return $attribs;
    }

    /**
     * Gets the date when a package will be ready to ship. Adjusts dates so
     * that they always fall on a weekday.
     *
     * @param <type> $ready_time timestamp for when the package will be ready
     * to ship, defaults to 10 days from current date
     */
    public function getReadyDate($ready_time = NULL) {
	if (is_null($ready_time)) {
	    $ready_time = strtotime('+10 days');
	}
	if (is_numeric($ready_time) && $ready_time >= strtotime(date('Y-m-d'))) {
	    $weekend_days = array('6', '7');
	    while (in_array(date('N', $ready_time), $weekend_days)) {
		$ready_time = strtotime('+1 day', $ready_time);
	    }
	    return $ready_time;
	}
    }

    /**
     * Gets selected simple product from configurable
     * (using the fact that getSku() on item returns selected simple product sku)
     * 
     * @param Mage_Sales_Model_Order_Item $item
     * @return Mage_Catalog_Model_Product
     */
    public function getSelectedSimpleProductFromConfigurable($item) {
	$simpleProductId = Mage::getModel('catalog/product')->getIdBySku($item->getSku());
	return Mage::getModel('catalog/product')->load($simpleProductId);
    }
    
    /**
     * Retrieve Default Temando Product Attributes from Configuration
     * 
     * @return array
     */
    public function getDefaultTemandoAttributes()
    {
	return array(
	    'temando_packaging_mode'  => 0, //defaults
	    'temando_packaging'	    => $this->getConfigData('defaults/packaging'),
	    'temando_fragile'	    => $this->getConfigData('defaults/fragile'),
	    'temando_length'	    => (float)$this->getConfigData('defaults/length'),
	    'temando_width'	    => (float)$this->getConfigData('defaults/width'),
	    'temando_height'	    => (float)$this->getConfigData('defaults/height'),
	);
    }
    
    /**
     * Converts given weight from configured unit to grams
     * 
     * @param float $value Weight to convert
     * @param string $currentUnit Current weight unit
     * @return float Converted weight in grams
     */
    public function getWeightInGrams($value, $currentUnit = null)
    {
	$value = floatval($value);
	$currentUnit = $currentUnit ? $currentUnit : $this->getConfigData('units/weight');
	//from units as specified in configuration
	switch($currentUnit) {
	    case Temando_Temando_Model_System_Config_Source_Unit_Weight::KILOGRAMS:
		return $value * 1000; break;
	    
	    case Temando_Temando_Model_System_Config_Source_Unit_Weight::OUNCES: 
		return $value * 28.3495; break;
	    
	    case Temando_Temando_Model_System_Config_Source_Unit_Weight::POUNDS: 
		return $value * 453.592; break;
	    
	    default: return $value; break;
	}
    }
    
    /**
     * Converts given distance from configured unit to centimetres
     * 
     * @param float $value Distance to convert
     * @param string $currentUnit Current measure unit 
     * @return float Converted distance in centimetres
     */
    public function getDistanceInCentimetres($value, $currentUnit = null)
    {
	$value = floatval($value);
	$currentUnit = $currentUnit ? $currentUnit : $this->getConfigData('units/measure');
	switch($currentUnit) {
	    case Temando_Temando_Model_System_Config_Source_Unit_Measure::METRES:
		return $value * 100; break;
	    
	    case Temando_Temando_Model_System_Config_Source_Unit_Measure::FEET:
		return $value * 30.48; break;
	    
	    case Temando_Temando_Model_System_Config_Source_Unit_Measure::INCHES:
		return $value * 2.54; break;
	    
	    default: return $value; break;
	}
    }

    /**
     * Returns Client ID from system configuration
     * 
     * @return string
     */
    public function getClientId() {
	return $this->getConfigData('general/client');
    }

    /**
     * Retrieves cached address validation results
     * 
     * @param string $words
     * @return array of results or false if no cache
     */
    public function getSuggestionsCache($words) {
	if (is_file($this->getFileCacheByWords($words))) {
	    return file_get_contents($this->getFileCacheByWords($words));
	}

	return false;
    }

    /**
     * Returns path to the cache file
     * 
     * @param string $words
     * @return string
     */
    public function getFileCacheByWords($words) {
	$key = md5(strtolower(join('_', $words)));
	$path = Mage::getBaseDir('cache');
	return $path . DS . 'temando' . DS . $key[0] . DS . $key[1] . DS . $key . '.cache';
    }

    /**
     * Register cached results 
     * (saves results into a cache file or removes file if results empty)
     * 
     * @param string $words
     * @param string $value
     * @return void
     */
    public function setSuggestionsCache($words, $value) {
	if (strlen(join(' ', $words)) > 4) {
	    return;
	}

	$file = $this->getFileCacheByWords($words);
	$dir = dirname($file);
	if (!is_dir($dir)) {
	    mkdir($dir, 0777, true);
	}

	$fd = @fopen($file, 'w');
	if (!$fd) {
	    return;
	}

	$size = fwrite($fd, $value);
	fclose($fd);
	if ($size != strlen($value)) {
	    unlink($file);
	}
    }

    /**
     * Returns region name saved in customers session
     * @return string|null
     */
    public function getSessionRegion() {
	$data = Mage::getSingleton('customer/session')->getData('estimate_product_shipping');
	if ($data) {
	    return Mage::getModel('directory/region')->load($data['region_id'])->getName();
	}

	return null;
    }

    /**
     * Returns city name saved in customers session
     * @return string|null
     */
    public function getSessionCity() {
	$data = Mage::getSingleton('customer/session')->getData('estimate_product_shipping');
	if ($data) {
	    return $data['city'];
	}

	return null;
    }

    /**
     * Returns postal code saved in customers session
     * @return string|null
     */
    public function getSessionPostcode() {
	$data = Mage::getSingleton('customer/session')->getData('estimate_product_shipping');
	if ($data) {
	    return $data['postcode'];
	}

	return null;
    }

    /**
     * Returns id of the region saved in customers session
     * @return int|null
     */
    public function getSessionRegionId() {
	$data = Mage::getSingleton('customer/session')->getData('estimate_product_shipping');
	if ($data) {
	    return $data['region_id'];
	}

	return null;
    }

    /**
     * Return list of available origin locations
     * 
     * @return array
     */
    public function getLocationList() {
	return array(
	    self::DEFAULT_WAREHOUSE_NAME => self::DEFAULT_WAREHOUSE_NAME
	);
    }
    
    /**
     * Returns location create/update request array
     * 
     * @return array
     */
    public function getOriginRequestArray(Varien_Object $data) {
	return array(
	    'description'   => self::DEFAULT_WAREHOUSE_NAME,
	    'type'	    => 'Origin',
	    'contactName'   => $data->getContactName(),
	    'companyName'   => $data->getCompanyName(),
	    'street'	    => $data->getStreet(),
	    'suburb'	    => $data->getCity(),
	    'state'	    => $data->getRegion(),
	    'code'	    => $data->getPostcode(),
	    'country'	    => $data->getCountry(),
	    'phone1'	    => $data->getPhone1(),
	    'phone2'	    => $data->getPhone2(),
	    'fax'	    => $data->getFax(),
	    'email'	    => $data->getEmail(),
	    'loadingFacilities' => $data->getLoadingFacilities() ? 'Y' : 'N',
	    'forklift'		=> $data->getForklift() ? 'Y' : 'N',
	    'dock'		=> $data->getDock() ? 'Y' : 'N',
	    'limitedAccess'	=> $data->getLimitedAccess() ? 'Y' : 'N',
	    'postalBox'		=> $data->getPobox() ? 'Y' : 'N'
	);
    }

    /**
     * Returns true if street address is a PO Box
     * 
     * @param string $street
     * @return boolean 
     */
    public function isStreetWithPO($street) {
	if (!is_string($street)) {
	    return false;
	}

	if (preg_match('/p[\. ]*o[\.]*\s*?box/', strtolower($street))) {
	    return true;
	}

	$templates = array('PO Box', 'P.O. Box', 'P.O Box', 'PO. Box', 'p o box', 'Post Office', 'Locked Bag', 'Lock Bag', 'Private Bag');
	foreach ($templates as $t) {
	    if (strpos(strtolower($street), strtolower($t)) !== false) {
		return true;
	    }
	}

	return false;
    }

    /**
     * Returns true if shipping quote is dynamic quote obtained
     * from Temando API
     * 
     * @param int $quote_id
     * @return boolean
     */
    public function isQuoteDynamic($quote_id) {
	$fixed_carriers = array(
	    Temando_Temando_Model_Carrier::FLAT_RATE,
	    Temando_Temando_Model_Carrier::FREE,
	);

	if (in_array($quote_id, $fixed_carriers)) {
	    return false;
	}
	
	return true;
    }
   
}