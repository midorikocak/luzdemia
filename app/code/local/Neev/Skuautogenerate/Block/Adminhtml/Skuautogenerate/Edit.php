<?php

class Neev_Skuautogenerate_Block_Adminhtml_Skuautogenerate_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'skuautogenerate';
        $this->_controller = 'adminhtml_skuautogenerate';
        
        $this->_updateButton('save', 'label', Mage::helper('skuautogenerate')->__('Save Rule'));
        $this->_updateButton('delete', 'label', Mage::helper('skuautogenerate')->__('Delete Rule'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('skuautogenerate_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'skuautogenerate_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'skuautogenerate_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {   
        if( Mage::registry('skuautogenerate_data') && Mage::registry('skuautogenerate_data')->getId() ) {
            return Mage::helper('skuautogenerate')->__("Edit Rule (%s)", $this->htmlEscape(Mage::registry('skuautogenerate_data')->getProductType()));
        } else {
            return Mage::helper('skuautogenerate')->__('Add Rule');
        }
    }
}