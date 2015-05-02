<?php
class ES_Newssubscribers_Block_Newssubscribers extends Mage_Core_Block_Template
{

    public function getCookieName()
    {
        return Mage::getStoreConfig('newsletter/general/cookiename');
    }

    public function getCookieLifeTime()
    {
        return Mage::getStoreConfig('newsletter/general/cookielifetime');
    }

    public function isActivePopUp()
    {
        return Mage::getStoreConfig('newsletter/general/isactive');
    }

    public function getTheme()
    {
        return Mage::getStoreConfig('newsletter/general/theme');
    }

    public function getFirstTitle()
    {
        return Mage::getStoreConfig('newsletter/general/firsttitle');
    }

    public function getSecondTitle()
    {
        return Mage::getStoreConfig('newsletter/general/secondtitle');
    }

    public function getText()
    {
        return Mage::getStoreConfig('newsletter/general/message');
    }
}