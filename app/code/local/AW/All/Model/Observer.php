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

class AW_All_Model_Observer
{
    public function prepareAWTabs($observer)
    {
        $tabsBlock = $observer->getBlock();
        if ($tabsBlock instanceof Mage_Adminhtml_Block_System_Config_Tabs) {
            foreach ($tabsBlock->getTabs() as $tab) {
                if ($tab->getId() != 'awall' || null === $tab->getSections()) {
                    continue;
                }
                $_sections = $tab->getSections()->getItems();
                $tab->getSections()->clear();

                $_sectionLabelList = array();
                $_sectionList = array();
                foreach ($_sections as $key => $_section) {
                    if (!in_array($key, array('awall'))) {
                        $_sectionLabelList[] = strtolower(str_replace(' ', '_', $_section->getLabel()));
                        $_sectionList[] = $_section;
                    }
                }
                array_multisort($_sectionLabelList, SORT_ASC, SORT_STRING, $_sectionList);

                foreach ($_sectionList as $_section) {
                    $tab->getSections()->addItem($_section);
                }


                if (array_key_exists('awall', $_sections)) {
                    $tab->getSections()->addItem($_sections['awall']);
                }
            }
        }
        return $this;
    }
}