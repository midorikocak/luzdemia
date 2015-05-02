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
class Toogas_Featuredpopup_Block_Adminhtml_Featuredpopup_Edit_Tab_Formpopup extends Mage_Adminhtml_Block_Widget_Form 

{
  protected function _prepareForm()
  {  
	  $form = new Varien_Data_Form();	
	  
	  $model = Mage::registry('featuredpopup_data');
	  
	  $fieldset = $form->addFieldset('featuredpopup_popup', array('legend'=>Mage::helper('featuredpopup')->__('Popup Settings')));
	  
      // TODO: css and js styles to choose here
      
      $fieldset->addField('delay_start', 'text', array(
          'label'     => Mage::helper('featuredpopup')->__('Delay Start'),
          'name'      => 'delay_start',
          'class'     => 'validate-zero-or-greater',          
	  ));
	  
      $fieldset->addField('delay_close', 'text', array(
          'label'     => Mage::helper('featuredpopup')->__('Delay Close'),
          'name'      => 'delay_close',
          'class'     => 'validate-zero-or-greater',          
	  ));	  
	  	       
      $fieldset->addField('priority', 'text', array(
          'label'     => Mage::helper('featuredpopup')->__('Priority'),
          'name'      => 'priority',
          'class'     => 'validate-zero-or-greater',         
	  ));
	  
      $fieldset->addField('opacity', 'text', array(
          'label'     => Mage::helper('featuredpopup')->__('Opacity'),
          'name'      => 'opacity',
          'class'     => 'validate-zero-or-greater',         
	  ));	
	  
	  $form->setValues($model->getData());
	  
	  $this->setForm($form);
      return parent::_prepareForm();
  }

  
}  
