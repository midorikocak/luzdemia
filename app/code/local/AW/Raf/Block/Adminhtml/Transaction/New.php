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


class AW_Raf_Block_Adminhtml_Transaction_New extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'awraf';
        $this->_controller = 'adminhtml_transaction';
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
        return $this->__('Create Transaction');
    }

    protected function _prepareLayout()
    {
        $this->_addButton('add_discounts', array(
            'label' => $this->__('Add Transaction'),
            'onclick' => 'saveRafDiscount()',
            'class' => 'save'
                ), 10);


        parent::_prepareLayout();

        $this->_removeButton('save');
    }

}
