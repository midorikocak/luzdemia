<?php
class Neev_Skuautogenerate_Block_Adminhtml_Skuautogenerate extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_skuautogenerate';
    $this->_blockGroup = 'skuautogenerate';
    // $msg = $this->__('If "Product Type" setting is not available then consider it "All Product Types(unique)" by default.');
   // Mage::getSingleton('adminhtml/session')->addNotice($msg);
    $this->_headerText = Mage::helper('skuautogenerate')->__('Rule Manager');
    $this->_addButtonLabel = Mage::helper('skuautogenerate')->__('Add Rule');
    parent::__construct();
  }
}