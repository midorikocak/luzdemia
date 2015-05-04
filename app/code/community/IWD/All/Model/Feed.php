<?php
class IWD_All_Model_Feed extends Mage_Core_Model_Abstract
{
    protected $_feedUrl = 'http://iwdextensions.com/news';
    
    protected $_frequency = 24; 

    protected function _construct()
    {
    	
    }
        
    public function getFeedUrl()
    {       
        return $this->_feedUrl;
    }

    public function checkUpdate()
    {
        if (($this->getFrequency() + $this->getLastUpdate()) > time()) {
            return $this;
        }
    	
        $feedData = array();

        $feedJson = $this->getFeedData();

        if ($feedJson && $feedJson->channel && $feedJson->channel->items) {
            foreach ($feedJson->channel->items as $item) {
                $feedData[] = array(
                	'out_id'      => (int)$item->entity_id,
                    'severity'      => (int)$item->severity,
                    'date_added'    => $this->getDate((string)$item->date_added),
                    'title'         => (string)$item->title,
                    'description'   => (string)$item->description,
                    'url'           => (string)$item->url,
                );
                
                $feedDataDefault[] = array(
                		
                		'severity'      => (int)$item->severity,
                		'date_added'    => $this->getDate((string)$item->date_added),
                		'title'         => (string)$item->title,
                		'description'   => (string)$item->description,
                		'url'           => (string)$item->url,
                );
            }

            if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(array_reverse($feedDataDefault));
                Mage::getModel('iwdall/notification')->parse(array_reverse($feedData));
            }

        }
        $this->setLastUpdate();

        return $this;
    }

    public function getDate($rssDate)
    {
        return gmdate('Y-m-d H:i:s', strtotime($rssDate));
    }

    public function getFrequency()
    {
        return  $this->_frequency  * 3600;
    }

    public function getLastUpdate()
    {
        return Mage::app()->loadCache('iwd_notifications_lastcheck');
    }

    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'iwd_notifications_lastcheck');
        return $this;
    }

    public function getFeedData()
    {
        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array(
            'timeout'   => 2
        ));
        $curl->write(Zend_Http_Client::GET, $this->getFeedUrl(), '1.0');
        $data = $curl->read();
        if ($data === false) {
            return false;
        }
        $data = preg_split('/^\r?$/m', $data, 2);
        $data = trim($data[1]);
        $curl->close();

        try {
            $json  = json_decode($data);
        }
        catch (Exception $e) {
            return false;
        }

        return $json;
    }   
}
