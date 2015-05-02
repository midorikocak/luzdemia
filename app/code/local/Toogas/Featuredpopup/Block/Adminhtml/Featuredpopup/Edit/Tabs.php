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
class Toogas_Featuredpopup_Block_Adminhtml_Featuredpopup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('featuredpopup_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('featuredpopup')->__('Featured Popup (Free)'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('featuredpopup')->__('Featured Popup'),
          'title'     => Mage::helper('featuredpopup')->__('Featured Popup'),
          'content'   => $this->getLayout()->createBlock('featuredpopup/adminhtml_featuredpopup_edit_tab_form')->toHtml(),
      ));
    
      $this->addTab('date_section', array(
          'label'     => Mage::helper('featuredpopup')->__('Date Settings'),
          'title'     => Mage::helper('featuredpopup')->__('Date Settings'),
          'content'   => $this->getLayout()->createBlock('featuredpopup/adminhtml_featuredpopup_edit_tab_formdate')
          ->toHtml(),
      ));
 
 
      
      $this->addTab('popup_section', array(
          'label'     => Mage::helper('featuredpopup')->__('Popup Settings'),
          'title'     => Mage::helper('featuredpopup')->__('Popup Settings'),
          'content'   => $this->getLayout()->createBlock('featuredpopup/adminhtml_featuredpopup_edit_tab_formpopup')->toHtml(),
      ));           
     
      return parent::_beforeToHtml();
  }


}