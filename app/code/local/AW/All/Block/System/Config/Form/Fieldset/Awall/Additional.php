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

class AW_All_Block_System_Config_Form_Fieldset_Awall_Additional extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);

        foreach ($element->getElements() as $field) {
            $html .= $field->toHtml();
        }

        $html .= "<tr>
            <td class=\"label\"></td>
            <td class=\"value\">
            <button class=\"scalable\" onclick=\"window.location='" . Mage::getSingleton('adminhtml/url')->getUrl('awall_admin/additional/index') . "'\" type=\"button\">
                <span>View Additional info</span>
            </button
            </td>
         </tr>
         ";
        $html .= $this->_getFooterHtml($element);

        return $html;
    }
}