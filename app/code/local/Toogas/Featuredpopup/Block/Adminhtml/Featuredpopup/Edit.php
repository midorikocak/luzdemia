<?php
/**
 * Toogas Lda.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA (End-User License Agreement)
 * that is bundled with this package in the file toogas_license-free.txt.
 * It is also available at this URL:
 * http://www.toogas.com/licences/toogas_license-free.txt
 *
 * @category   Toogas
 * @package    Toogas_Featuredpopup
 * @copyright  Copyright (c) 2011 Toogas Lda. (http://www.toogas.com)
 * @license    http://www.toogas.com/licences/toogas_license-free.txt
 */
class Toogas_Featuredpopup_Block_Adminhtml_Featuredpopup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'featuredpopup';
        $this->_controller = 'adminhtml_featuredpopup';
        
        $this->_updateButton('save', 'label', Mage::helper('featuredpopup')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('featuredpopup')->__('Delete'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('featuredpopup_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'featuredpopup_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'featuredpopup_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('featuredpopup_data') && Mage::registry('featuredpopup_data')->getId() ) {
            return Mage::helper('featuredpopup')->__("Edit '%s'. (Free) <a target=_blank href=\"http://www.toogas.com/featured-pop-up.html?___store=en&___from_store=pt\">Click here for the Pro Version</a>", $this->htmlEscape(Mage::registry('featuredpopup_data')->getPopupName()));
        } else {
            return Mage::helper('featuredpopup')->__('New Featured Popup (Free) <a target=_blank href="http://www.toogas.com/featured-pop-up.html?___store=en&___from_store=pt">Click here for the Pro Version</a>');
        }
    }
}