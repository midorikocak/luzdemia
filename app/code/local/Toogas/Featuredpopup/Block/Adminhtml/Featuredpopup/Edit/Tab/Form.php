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
class Toogas_Featuredpopup_Block_Adminhtml_Featuredpopup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      
      $model = Mage::registry('featuredpopup_data'); //importante nas abas pa saber onde grava
      
      
      $fieldset = $form->addFieldset('featuredpopup_form', 
      array('legend'=>Mage::helper('featuredpopup')->__('Featured Popup (Free)')));
		
      $fieldset->addField('popup_name', 'text', array(
          'label'     => Mage::helper('featuredpopup')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'popup_name',
          'maxlength' => '30'
      ));
      
      
      $fieldset->addField('image_link', 'image', array(
          'label'     => Mage::helper('featuredpopup')->__('Popup Image'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'image_link',
	  ));

      $fieldset->addField('width_image', 'text', array(
          'label'     => Mage::helper('featuredpopup')->__('Width Image'),
          'class'     => 'required-entry validate-digits',
          'required'  => true,          
          'name'      => 'width_image',          
	  ));
	  
      $fieldset->addField('height_image', 'text', array(
          'label'     => Mage::helper('featuredpopup')->__('Height Image'),
          'class'     => 'required-entry validate-digits',
          'required'  => true,          
          'name'      => 'height_image',          
	  ));	  	                  

      $fieldset->addField('url_link', 'text', array(
          'label'     => Mage::helper('featuredpopup')->__('Url'),
          'name'      => 'url_link',
	  ));
	  
      $fieldset->addField('is_active', 'select', array(
          'label'     => Mage::helper('featuredpopup')->__('Status'),
          'options' => $this->helper('featuredpopup/data')->sacaStatus(),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'is_active',
	  ));  
	  
	        
      if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('featuredpopup')->__('Store View'),
                'title'     => Mage::helper('featuredpopup')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            //$model->setStoreId(Mage::app()->getStore(true)->getId()); //isto nao devera ser necessario para este caso
      }
     
      if ( Mage::getSingleton('adminhtml/session')->getFeaturedpopupData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFeaturedpopupData());
          Mage::getSingleton('adminhtml/session')->setFeaturedpopupData(null);
      } elseif ( Mage::registry('featuredpopup_data') ) {
          $form->setValues(Mage::registry('featuredpopup_data')->getData());
      }
      $form->setValues($model->getData()); //coloquei
      $this->setForm($form);
      
      return parent::_prepareForm();
  }

  
  
  
}