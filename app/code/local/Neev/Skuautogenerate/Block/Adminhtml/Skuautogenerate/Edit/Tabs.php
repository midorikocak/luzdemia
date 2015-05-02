<?php

class Neev_Skuautogenerate_Block_Adminhtml_Skuautogenerate_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('skuautogenerate_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('skuautogenerate')->__('Rule Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('skuautogenerate')->__('Rule Information'),
          'title'     => Mage::helper('skuautogenerate')->__('Rule Information'),
          'content'   => $this->getLayout()->createBlock('skuautogenerate/adminhtml_skuautogenerate_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}