<?php

/**
 * @method Temando_Temando_Model_Carrier setCarrierId()
 * @method Temando_Temando_Model_Carrier setCompanyName()
 * @method Temando_Temando_Model_Carrier setCompanyContact()
 * @method Temando_Temando_Model_Carrier setStreetAddress()
 * @method Temando_Temando_Model_Carrier setStreetSuburb()
 * @method Temando_Temando_Model_Carrier setStreetCity()
 * @method Temando_Temando_Model_Carrier setStreetState()
 * @method Temando_Temando_Model_Carrier setStreetPostode()
 * @method Temando_Temando_Model_Carrier setStreetCountry()
 * @method Temando_Temando_Model_Carrier setPostalAddress()
 * @method Temando_Temando_Model_Carrier setPostalSuburb()
 * @method Temando_Temando_Model_Carrier setPostalCity()
 * @method Temando_Temando_Model_Carrier setPostalState()
 * @method Temando_Temando_Model_Carrier setPostalPostcode()
 * @method Temando_Temando_Model_Carrier setPostalCountry()
 * @method Temando_Temando_Model_Carrier setPhone()
 * @method Temando_Temando_Model_Carrier setEmail()
 * @method Temando_Temando_Model_Carrier setWebsite()
 *
 * @method string getCarrierId()
 * @method string getCompanyName()
 * @method string getCompanyContact()
 * @method string getStreetAddress()
 * @method string getStreetSuburb()
 * @method string getStreetCity()
 * @method string getStreetState()
 * @method string getStreetPostode()
 * @method string getStreetCountry()
 * @method string getPostalAddress()
 * @method string getPostalSuburb()
 * @method string getPostalCity()
 * @method string getPostalState()
 * @method string getPostalPostcode()
 * @method string getPostalCountry()
 * @method string getPhone()
 * @method string getEmail()
 * @method string getWebsite()
 */
class Temando_Temando_Model_Carrier extends Mage_Core_Model_Abstract
{
    
    const FLAT_RATE = 'flat';
    const FREE      = 'free';
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('temando/carrier');
    }
    
}
