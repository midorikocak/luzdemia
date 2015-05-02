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


class AW_Raf_Block_Adminhtml_Edit_Renderer_CustomerName
    extends Mage_Adminhtml_Block_Template implements Varien_Data_Form_Element_Renderer_Interface
{

    protected function _construct()
    {
        $this->setTemplate('aw_raf/edit/renderer/customer_name.phtml');
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    public function getCustomerUrl()
    {
        return Mage::helper('adminhtml')->getUrl(
            'adminhtml/customer/edit',
            array('id' => $this->getElement()->getCustomer()->getId())
        );
    }

}
