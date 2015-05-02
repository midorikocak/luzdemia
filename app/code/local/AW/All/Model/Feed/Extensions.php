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

class AW_All_Model_Feed_Extensions extends AW_All_Model_Feed_Abstract
{

    /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl()
    {
        return AW_All_Helper_Config::EXTENSIONS_FEED_URL;
    }


    /**
     * Checks feed
     * @return
     */
    public function check()
    {
        if (!(Mage::app()->loadCache('aw_all_extensions_feed')) || (time() - Mage::app()->loadCache('aw_all_extensions_feed_lastcheck')) > Mage::getStoreConfig('awall/feed/check_frequency')) {
            $this->refresh();
        }
    }

    public function refresh()
    {
        $exts = array();
        try {
            $Node = $this->getFeedData();
            if (!$Node) return false;
            foreach ($Node->children() as $ext) {
                $exts[(string)$ext->name] = array(
                    'display_name' => (string)$ext->display_name,
                    'version' => (string)$ext->version,
                    'url' => (string)$ext->url
                );
            }

            Mage::app()->saveCache(serialize($exts), 'aw_all_extensions_feed');
            Mage::app()->saveCache(time(), 'aw_all_extensions_feed_lastcheck');
            return true;
        } catch (Exception $E) {
            return false;
        }
    }

    public function checkExtensions()
    {
        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        sort($modules);

        $magentoPlatform = AW_All_Helper_Versions::getPlatform();
        foreach ($modules as $extensionName) {
            if (strstr($extensionName, 'AW_') === false) {
                continue;
            }
            if ($extensionName == 'AW_Core' || $extensionName == 'AW_All') {
                continue;
            }
            if ($platformNode = $this->getExtensionPlatform($extensionName)) {
                $extensionPlatform = AW_All_Helper_Versions::convertPlatform($platformNode);
                if ($extensionPlatform < $magentoPlatform) {
                    $this->disableExtensionOutput($extensionName);
                }
            }
        }
        return $this;
    }

    public function getExtensionPlatform($extensionName)
    {
        try {
            if ($platform = Mage::getConfig()->getNode("modules/$extensionName/platform")) {
                $platform = strtolower($platform);
                return $platform;
            } else {
                throw new Exception();
            }
        } catch (Exception $e) {
            return false;
        }
    }


    public function disableExtensionOutput($extensionName)
    {
        $coll = Mage::getModel('core/config_data')->getCollection();
        $coll->getSelect()->where("path='advanced/modules_disable_output/$extensionName'");
        $i = 0;
        foreach ($coll as $cd) {
            $i++;
            $cd->setValue(1)->save();
        }
        if ($i == 0) {
            Mage::getModel('core/config_data')
                    ->setPath("advanced/modules_disable_output/$extensionName")
                    ->setValue(1)
                    ->save();
        }
        return $this;
    }


}