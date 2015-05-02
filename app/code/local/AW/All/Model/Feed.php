<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Raf
 * @version    2.1.5
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_All_Model_Feed extends Mage_AdminNotification_Model_Feed
{
    const XML_USE_HTTPS_PATH = 'awall/feed/use_https';
    const XML_FEED_URL_PATH = 'awall/feed/url';
    const XML_FREQUENCY_PATH = 'awall/feed/check_frequency';
    const XML_FREQUENCY_ENABLE = 'awall/feed/enabled';
    const XML_LAST_UPDATE_PATH = 'awall/feed/last_update';


    public static function check()
    {
        if (!Mage::getStoreConfig(self::XML_FREQUENCY_ENABLE)) {
            return;
        }
        return Mage::getModel('awall/feed')->checkUpdate();
    }

    public function getFrequency()
    {
        return Mage::getStoreConfig(self::XML_FREQUENCY_PATH) * 3600;
    }

    public function getLastUpdate()
    {
        //return 100;
        return Mage::app()->loadCache('awall_notifications_lastcheck');
    }

    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'awall_notifications_lastcheck');
        return $this;
    }

    public function getFeedUrl()
    {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://')
                              . Mage::getStoreConfig(self::XML_FEED_URL_PATH);
        }
        return $this->_feedUrl;
    }

    public function checkUpdate()
    {
        if (($this->getFrequency() + $this->getLastUpdate()) > time()) {
            return $this;
        }

        $feedData = array();

        $feedXml = $this->getFeedData();

        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $feedData[] = array(
                    'severity' => (int)$item->severity ? (int)$item->severity : 3,
                    'date_added' => $this->getDate((string)$item->pubDate),
                    'title' => (string)$item->title,
                    'description' => (string)$item->description,
                    'url' => (string)$item->link,
                );
            }
            if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(array_reverse($feedData));
            }

        }
        $this->setLastUpdate();

        return $this;
    }

}
