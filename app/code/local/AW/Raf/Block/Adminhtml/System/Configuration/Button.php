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

class AW_Raf_Block_Adminhtml_System_Configuration_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $helper = Mage::helper('awraf');

        $url = $this->getUrl("awraf_admin/adminhtml_rules/resetAll");
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setClass('scalable')
                ->setLabel($helper->__('Reset all rule activities'))
                ->setOnClick("return conformation ();")
                ->toHtml();

        $html .= "<p class='note'>";
        $html .= "<span style='color:#E02525;'>";
        $html .= $helper->__("This action is unrecoverable and will reset all rule activities and statistics");
        $html .= "</span>";
        $html .= "</p>";

        $html .= "<script  type='text/javascript'>
                            function conformation (){
                                if (confirm('" . $helper->__('Are you sure?') . "')){
                                    setLocation('$url');
                                }
                            }
                       </script>";

        return $html;
    }

}
