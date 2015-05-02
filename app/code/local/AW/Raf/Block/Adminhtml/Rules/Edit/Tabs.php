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


class AW_Raf_Block_Adminhtml_Rules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('awraf_info_tabs');
        $this->setDestElementId('edit_form');       
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label' => $this->__('Rule Details'),
            'title' => $this->__('Rule Details'),
            'content' => $this->getLayout()->createBlock('awraf/adminhtml_rules_edit_tab_general')->toHtml()
        ));
      
        return parent::_beforeToHtml();
    }
}
