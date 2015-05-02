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


class AW_Raf_Block_Share extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    const TYPE_MAIN = 1;
    const TYPE_CURRENT = 2;
    const TYPE_CUSTOM = 3;

    const TWITTER_URL  = 'https://twitter.com/home?status=';
    const FACEBOOK_URL = 'https://www.facebook.com/share.php?u=';
    const MYSPACE_URL  = 'http://www.myspace.com/Modules/PostTo/Pages/?u=';

    protected function _toHtml()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return;
        }
        $this->setTemplate('aw_raf/share.phtml');
        return $this->renderView(); 
    }

    public function getRefUrl()
    {
        $urlKey = null;
        $session = Mage::getSingleton('customer/session');       
        if ($session->isLoggedIn()) {
            $id = $session->getCustomer()->getId();
            $urlKey = array('ref' => $this->helper('awraf')->encodeUrlKey($id));            
        }
      
        switch($this->getLinkType()) {            
            case self::TYPE_CURRENT:                
                $url = Mage::helper('core/url')->getCurrentUrl();                 
                break;
            case self::TYPE_MAIN:               
                $url = $this->helper('awraf')->getReferrerLink();
                break;
            case self::TYPE_CUSTOM:
                $url = $this->getCustomPage();
                break;
            default:
                break;
        } 
       
        if (Mage::app()->getStore(true)->isCurrentlySecure()) {
            $url = str_replace("http://", 'https://', $url);
        }
        if (strpos($url, '___store=') === false) {
            $url = $this->addRequestParam($url, array('___store' => Mage::app()->getStore()->getCode()));
        }  
        if ($urlKey && (strpos($url, 'ref=') === false)) {
           $url = $this->addRequestParam($url, $urlKey);
        }
        
        return urlencode($url);       
    }
    
    /* addRequestParam core/url (1.7.x) helper for 1.4.x compatibility */
    public function addRequestParam($url, $param)
    {
        $startDelimiter = (false === strpos($url, '?'))?'?':'&';

        $arrQueryParams = array();
        foreach ($param as $key => $value) {
            if (is_numeric($key) || is_object($value)) {
                continue;
            }
            if (is_array($value)) {              
                $arrQueryParams[] = $key . '[]=' . implode('&' . $key . '[]=', $value);
            } elseif (is_null($value)) {
                $arrQueryParams[] = $key;
            } else {
                $arrQueryParams[] = $key . '=' . $value;
            }
        }
        $url .= $startDelimiter . implode('&', $arrQueryParams);
        return $url;
    }

    public function getTwitterUrl()
    {
        return self::TWITTER_URL . $this->getRefUrl();
    }

    public function getFacebookUrl()
    {
        return self::FACEBOOK_URL . $this->getRefUrl();
    }

    public function getMyspaceUrl()
    {
        return self::MYSPACE_URL . $this->getRefUrl();
    }
}