<?php

/**
 * @method int getId()
 * @method int getOrderId()
 * @method string getCustomerSelectedQuoteId()
 * @method string getCustomerSelectedQuoteDescription()
 * @method string getAdminSelectedQuoteId()
 * @method float getAnticipatedCost()
 * @method int getStatus()
 * @method int getBookingRequestId()
 * @method boolean getInsurance()
 * @method string getDestinationCountry()
 * @method string getDestinationPostcode()
 * @method string getDestinationCity()
 * @method string getReadyDate()
 * @method string getReadyTime()
 * @method string getCustomerSelectedOptions()
 * @method string getOrderItems()
 *
 * @method Temando_Temando_Model_Shipment setId()
 * @method Temando_Temando_Model_Shipment setOrderId()
 * @method Temando_Temando_Model_Shipment setCustomerSelectedQuoteId()
 * @method Temando_Temando_Model_Shipment setCustomerSelectedQuoteDescription()
 * @method Temando_Temando_Model_Shipment setAdminSelectedQuoteId()
 * @method Temando_Temando_Model_Shipment setAnticipatedCost()
 * @method Temando_Temando_Model_Shipment setStatus()
 * @method Temando_Temando_Model_Shipment setBookingRequestId()
 * @method Temando_Temando_Model_Shipment setDestinationCountry()
 * @method Temando_Temando_Model_Shipment setDestinationPostcode()
 * @method Temando_Temando_Model_Shipment setDestinationCity()
 * @method Temando_Temando_Model_Shipment setReadyDate()
 * @method Temando_Temando_Model_Shipment setReadyTime()
 * @method Temando_Temando_Model_Shipment setCustomerSelectedOptions()
 * @method Temando_Temando_Model_Shipment setOrderItems()
 *
 */
class Temando_Temando_Model_Shipment extends Mage_Core_Model_Abstract
{
    
    /**
     * @var Mage_Sales_Model_Order
     */
    protected $_order = null;
    
    /**
     * @var array
     */
    protected $_boxes = null;
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/shipment');
    }
    
    /**
     * Overridden to enforce the use of custom getters.
     *
     * Certain core functions use (e.g.) getData('created_at') instead of
     * getCreatedAt(). This makes sure the correct data is returned.
     *
     * Using _afterLoad() to load the Magento order and populate
     * $this->_data['created_at'] won't work: _afterLoad() isn't called
     * when objects are loaded in a collection.
     *
     * @see Varien_Object::getData()
     */
    public function getData($key = '', $index = null)
    {
        switch ($key) {
            case 'selected_quote_description': return $this->getSelectedQuoteDescription();
            case 'created_at':                 return $this->getCreatedAt();
            case 'order_number':               return $this->getOrderNumber();
            case 'shipping_paid':              return $this->getShippingPaid();
            default:
        }
        return parent::getData($key, $index);
    }
    
    /**
     * Gets the Magento order associated with this shipment.
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if (!$this->_order && $this->getId()) {
            $this->_order = Mage::getModel('sales/order')->load($this->getOrderId());
        }
        return $this->_order;
    }
    
    /**
     * Gets the creation date of this shipment.
     */
    public function getCreatedAt()
    {
        if ($this->getOrder()) {
            return $this->getOrder()->getCreatedAt();
        }
        return null;
    }
    
    /**
     * Gets the Magento order number (as shown to customers, e.g. 100000123)
     */
    public function getOrderNumber()
    {
        if ($this->getOrder()) {
            return $this->getOrder()->getIncrementId();
        }
        return null;
    }
    
    public function getBoxes()
    {
        return Mage::getModel('temando/box')->getCollection()
            ->addFieldToFilter('shipment_id', $this->getId());
    }
    
    /**
     * Gets the carrier description.
     *
     * If the Temando shipment status is Pending or Cancelled, this will be the
     * shipment method selected by the user during checkout. If the Temando
     * shipment status is Booked, then it will describe the booked quote.
     *
     * @return string
     */
    public function getSelectedQuoteDescription()
    {
        $quote = $this->getSelectedQuotePermutation();
        if (is_object($quote) && $quote->getId()) {
            return $quote->getDescription();
        } else {
            return $this->getCustomerSelectedQuoteDescription();
        }
        return null;
    }
    
    /**
     * Gets the selected quote.
     *
     * If the Temando shipment status is Pending or Cancelled, this will be the
     * quote selected by the user during checkout. If the Temando shipment
     * status is Booked, then it will be the booked quote.
     *
     * @return Temando_Temando_Model_Quote
     */
    public function getSelectedQuote()
    {
        $quote = null;
        
        switch ($this->getStatus()) {
            case Temando_Temando_Model_System_Config_Source_Shipment_Status::BOOKED:
                $quote = Mage::getModel('temando/quote')
                    ->load($this->getAdminSelectedQuoteId());
                break;
            case Temando_Temando_Model_System_Config_Source_Shipment_Status::PENDING:
            default:
                $quote = Mage::getModel('temando/quote')
                    ->load($this->getCustomerSelectedQuoteId());
                break;
        }
        
        return $quote;
    }
    
    /**
     * Gets the quote using $this->getQuote(), and applies the selected
     * options to it as well.
     *
     * @return Temando_Temando_Model_Quote
     */
    public function getSelectedQuotePermutation()
    {
        $quote = $this->getSelectedQuote();
        
        /* @var $quote Temando_Temando_Model_Quote */
        if ($quote !== null) {
            $options = Mage::getModel('temando/options');
            /* @var $options Temando_Temando_Model_Options */

            $option_array = $this->getOptions();   
            foreach ($option_array as $option) {
                $options->addItem($option);
            }
            
            $permutations = $options->applyAll($quote);
            
            if ($permutations) {
                return reset($permutations);
            }
        }
    }
    
    /**
     * Gets the shipping amount the customer paid on this order.
     */
    public function getShippingPaid()
    {
        if ($this->getOrder()) {
            return $this->getOrder()->getShippingAmount();
        }
        return null;
    }
    
    /**
     * Fixes up database-style dates after loading.
     *
     * @see Mage_Core_Model_Abstract::_afterLoad()
     */
    public function _afterLoad()
    {
        if ($this->getData('ready_date') == '0000-00-00') {
            $this->unsReadyDate();
        }
        if ($this->getData('ready_time') == '') {
            $this->unsReadyTime();
        }
    }
    
    /**
     * Gets Temando quotes for this shipment.
     */
    public function fetchQuotes($username = null, $password = null, $sandbox = false)
    {        
        /* @var $request Temando_Temando_Model_Api_Request */
        
	$request = Mage::getModel('temando/api_request');
        $request
            ->setUsername($username)
            ->setPassword($password)
            ->setSandbox($sandbox)
            ->setMagentoQuoteId($this->getOrder()->getQuoteId())
            ->setDestination(
                $this->getDestinationCountry(),
                $this->getDestinationPostcode(),
                $this->getDestinationCity(),
                $this->getDestinationStreet())
            ->setItems($this->getBoxes());
        if ($this->getReadyDate()) {
            $request->setReady(strtotime($this->getReadyDate()), $this->getReadyTime());
        } else {
            $request->setReady(null);
        }

        $allowed_carriers = explode(',', Mage::getStoreConfig('carriers/temando/allowed_methods'));
        $request->setAllowedCarriers($allowed_carriers);

        $request->getQuotes();
    }
    
    /**
     * Clears all quotes from the database relating to this shipment.
     */
    public function clearQuotes()
    {
        $old_quotes = Mage::getModel('temando/quote')->getCollection()
            ->addFieldToFilter('magento_quote_id', $this->getOrder()->getQuoteId());
        foreach ($old_quotes as $quote) {
            /* @var $quote Temando_Temando_Model_Quote */
            $quote->delete();
        }
        return $this;
    }
    
    
    /**
     * Gets the quotes for this shipment from the database
     *
     * @param boolean $use_options
     *
     * @return Temando_Temando_Model_Mysql4_Quote_Collection
     */
    public function getQuotes($use_options = false)
    {
        if ($use_options) {
            $option_array = $this->getOptions();
            $options = Mage::getModel('temando/options');
            /* @var $options Temando_Temando_Model_Options */
            foreach ($option_array as $option) {
                $options->addItem($option);
            }

            /* @var $quotes Temando_Temando_Model_Mysql4_Quote_Collection */
            $quotes = Mage::getModel('temando/quote')->getCollection();
            if (!$this->getQuotes()->count()) {
                $quotes
                    ->addFieldToFilter('magento_quote_id', $this->getOrder()->getQuoteId());
            } else {
                foreach ($this->getQuotes() as $quote_id => $quote) {
                    /* @var $quote Temando_Temando_Model_Quote */
                    $permutations = $options->applyAll($quote);
                    if ($add_quote = reset($permutations)) {
                        $quotes->addItem($add_quote);
                    }
                }
            }

            return $quotes;
        } else {
            return Mage::getModel('temando/quote')->getCollection()
                ->addFieldToFilter('magento_quote_id', $this->getOrder()->getQuoteId());
        }
    }
    
    
    /**
     * Gets the customer selected options as an array.
     *
     * e.g. for a options string like "insurance_0_carbon_1", this will give:
     *
     * array(
     *     'insurance' => 0,
     *     'carbon'    => 1,
     * )
     *
     * @return array
     */
    public function getOptionsArray()
    {
        $string = $this->getCustomerSelectedOptions();
        $elements = explode('_', $string);
        
        $options = array();
        
        for ($i = 0; $i < count($elements); $i += 2) {
            $options[$elements[$i]] = $elements[$i + 1];
        }
        
        return $options;
    }
    
    /**
     * Gets the customer selected options as an array of Option objects.
     *
     * Each Option object will have the "forced value" set to the value that
     * the customer selected.
     *
     * @return array
     */
    public function getOptions()
    {
        $options = array();
        foreach ($this->getOptionsArray() as $id => $value) {
            // try to instantiate model based on option ID
            if ('carbon' == $id) {
                $id = 'carbonoffset';
            }

            if ('insurance' == $id) {
                $_t = $this->getData();
                if (isset($_t['id'])) {
                    $i_id = $_t['id'];
                    if (!is_null(Mage::getSingleton('adminhtml/session')->getData('insurance_' . $i_id))) {
                        $value = Mage::getSingleton('adminhtml/session')->getData('insurance_' . $i_id);
                    }
                }
            }
	    try {
		$option = Mage::getModel('temando/option_' . $id);
	    } catch(Exception $e) {
		$option = false;
	    }
            if ($option) {
                /* @var $option Temando_Temando_Model_Option_Abstract */
                $option->setForcedValue($value);
                $options[] = $option;
            }
        }
        
        return $options;
    }
    
    /**
     * Checks wether this shipment can be shipped
     * 
     * @return boolean
     */
    public function isStatusOpened()
    {
	return 
	    $this->getStatus() == Temando_Temando_Model_System_Config_Source_Shipment_Status::PENDING;
    }
    
    /**
     * Is this shipment a TZ pickup?
     * 
     * @return boolean
     */
    public function isPickup()
    {
	return (boolean)$this->getPickupDescription();
    }
    
}
