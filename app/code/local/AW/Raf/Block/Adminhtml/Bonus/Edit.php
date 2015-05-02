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


class AW_Raf_Block_Adminhtml_Bonus_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'awraf';
        $this->_controller = 'adminhtml_bonus';

        $this->_formScripts[] = "
            document.observe('dom:loaded', function() {
                var fn = function() {
                    try {
                       $('customer_fieldset_massaction-select').up('div.right').remove();
                    } catch(e) { }
                };
                fn();
                var originalInitGridAjaxFn = customer_fieldsetJsObject.initGridAjax.bind(customer_fieldsetJsObject);
                customer_fieldsetJsObject.initGridAjax = function(){
                    originalInitGridAjaxFn();
                    fn();
                };
            });

            function saveAndContinueEdit(url) {
                if (typeof customer_fieldset_massactionJsObject != 'undefined') {
                    $('selected_values').value = customer_fieldset_massactionJsObject.getCheckedValues();
                }

                editForm.submit(url);
            }

            function saveRafDiscount() {

                if (typeof customer_fieldset_massactionJsObject != 'undefined') {
                    $('selected_values').value = customer_fieldset_massactionJsObject.getCheckedValues();
                }

                editForm.submit();
            }
        ";

        parent::__construct();
    }

    public function getHeaderText()
    {
        $discountModel = Mage::registry('awraf_discount');
        if ($discountModel->getId()) {
            if ($discountModel->getRuleName()) {
                return $this->__("Edit Discount '%s'", $this->escapeHtml($discountModel->getRuleName()));
            }
            return $this->__("Edit Discount #'%s'", $this->escapeHtml($discountModel->getId()));
        } else {
            return $this->__('Create Discount');
        }
    }

    protected function _prepareLayout()
    {
        $discountModel = Mage::registry('awraf_discount');
        parent::_prepareLayout();
        if ($discountModel->getId()) {
            $this->_addButton('save_and_continue', array(
                'label' => $this->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit(\'' . $this->_getSaveAndContinueUrl() . '\')',
                'class' => 'save'
            ), 10);
        } else {
            $this->_removeButton('save');
            $this->_addButton('add_discount', array(
                'label' => $this->__('Add Discount'),
                'onclick' => 'saveRafDiscount()',
                'class' => 'save'
            ), 10);
        }
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
                '_current' => true,
                'back' => 'edit',
                'tab' => '{{tab_id}}'
            )
        );
    }

}
