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

class AW_Raf_Block_Adminhtml_Grid_Renderer_RuleName extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
    public function _getValue(Varien_Object $row)
    {
        $html = $this->__('-');
        if (null !== $row->getRuleId()) {
            $html = sprintf('<span style="color:#ff0000;">%s</span>', $this->__('Removed'));
        }

        if (null !== $row->getRuleId() && trim($row->getRuleName()) != '') {
            $url = Mage::helper('adminhtml')->getUrl(
                'awraf_admin/adminhtml_rules/edit',
                array('id' => $row->getRuleId())
            );
            $html = sprintf('<a href="%s">%s</a>', $url, trim($row->getRuleName()));
        }
        return $html;
    }
}