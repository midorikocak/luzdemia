<?php

class Temando_Temando_Model_Api_Request_Anytime extends Mage_Core_Model_Abstract {

    /**
     * @var string
     */
    protected $_ready_time_of_day = null;

    /**
     * @var string
     */
    protected $_ready_date = null;

    public function _construct() {
	parent::_construct();
	$this->_init('temando/api_request_anytime');
    }

    /**
     * Set the date the product will be ready (in UTC).
     *
     * If setting the ready date to a weekend, the next week day will be used instead.
     *
     * Doesn't accept timestamps in the past.
     *
     * @param <type> $timestamp The timestamp when the package will be ready
     * (only the date information is used, the time of day is set separately)
     *
     */
    public function setReadyDate($timestamp = null) {
	$this->_ready_date = Mage::helper('temando')->getReadyDate($timestamp);
	return $this;
    }

    public function setReadyTimeOfDay($time_of_day = 'AM') {
	if (strtoupper($time_of_day) === 'AM' || strtoupper($time_of_day) === 'PM') {
	    $this->_ready_time_of_day = strtoupper($time_of_day);
	}
	return $this;
    }

    public function validate() {
	return
		($this->_ready_time_of_day == 'AM' || $this->_ready_time_of_day == 'PM') &&
		is_numeric($this->_ready_date);
    }

    public function toRequestArray() {
	if (!$this->validate()) {
	    return false;
	}

	return array(
	    'readyDate' => date('Y-m-d', $this->_ready_date),
	    'readyTime' => $this->_ready_time_of_day,
	);
    }

}
