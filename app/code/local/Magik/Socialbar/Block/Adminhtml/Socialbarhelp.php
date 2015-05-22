<?php
 
class Magik_Socialbar_Block_Adminhtml_Socialbarhelp extends Mage_Core_Block_Template
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_help';
        $this->_blockGroup = 'adminhtml_help';
        $this->_headerText = Mage::helper('socialbar')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('socialbar')->__('Add Item');
        parent::__construct();
    }
}