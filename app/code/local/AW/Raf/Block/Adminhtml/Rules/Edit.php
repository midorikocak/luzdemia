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


class AW_Raf_Block_Adminhtml_Rules_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'awraf';
        $this->_controller = 'adminhtml_rules';
        $this->_formScripts[] = "
            function saveAndContinueEdit(url) {
                editForm.submit(
                        url.replace(/{{tab_id}}/ig, awraf_info_tabsJsTabs.activeTab.id)
                );
            }

            document.observe('dom:loaded', function() {

                if ($('rule_type') && $('rule_use_rest_amount')) {
                    $('rule_use_rest_amount').up('tr').hide();
                }

                if ($('rule_type') && $('rule_limit')) {
                    $('rule_limit').up('tr').hide();
                }

                $$('.awraf-action-type').invoke('observe','change',function(event) {
                    var element = Event.element(event);
                    var index = element[element.selectedIndex].value;
                    editForm.validator.reset();
                    if (index == 2) {
                        $('note_action').hide();
                        $('rule_action').addClassName('validate-percents');
                    } else {
                        $('note_action').show();
                        if ($('rule_action').hasClassName('validate-percents')) {
                            $('rule_action').removeClassName('validate-percents');
                        }
                    }
                });

                var options = $('rule_type').options;
                if ($('note_target')) {
                    $('note_target').hide();
                }

                for(i=0; i<options.length;i++) {
                    if (options[i].value == options[options['selectedIndex']].value) {
                       continue;
                    }
                    $('rule_type_' + options[i].value).up('tr').setStyle({display:'none'});
                }

                Event.observe('rule_type', 'change', function(event) {
                    editForm.validator.reset();
                    var element = Event.element(event);
                    var index = element[element.selectedIndex].value;
                    awRafHideAll();
                    $('rule_type_' + index).up('tr').style.display = null;
                    $('note_target').show();
                    $('note_action').show();
                    if (index == 1 || index == 3) {
                       $('note_target').hide();
                    }

                    if (index != 1) {
                        $('rule_use_rest_amount').up('tr').show();
                        $('rule_limit').up('tr').show();
                    } else {
                        $('rule_use_rest_amount').up('tr').hide();
                        $('rule_limit').up('tr').hide();
                    }

                });
                function awRafHideAll() {
                    var options = $('rule_type').options;
                     for(i=0; i<options.length;i++) {
                        $('rule_type_' + options[i].value).up('tr').setStyle({display:'none'});
                     }
                }
            });"
        ;
        parent::__construct();
    }

    public function getHeaderText()
    {
        $rule = Mage::registry('awraf_rule');
        if ($rule->getId()) {
            if ($rule->getRuleName()) {
                return $this->__("Edit Rule '%s'", $this->escapeHtml($rule->getRuleName()));
            }
            return $this->__("Edit Rule #'%s'", $this->escapeHtml($rule->getId()));
        } else {
            return $this->__('Create New Rule');
        }
    }

    protected function _prepareLayout()
    {
        $this->_addButton('save_and_continue', array(
                'label' => $this->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit(\'' . $this->_getSaveAndContinueUrl() . '\')',
                'class' => 'save'
            ), 10);

        parent::_prepareLayout();
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