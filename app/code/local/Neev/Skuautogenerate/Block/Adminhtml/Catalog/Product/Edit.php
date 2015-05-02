<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Customer edit block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Neev_Skuautogenerate_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Catalog_Product_Edit
{

    protected function _prepareLayout()
    {
        if (!$this->getRequest()->getParam('popup')) {
            $this->setTemplate('skuautogenerate/catalog/product/edit.phtml');

            $this->setChild('back_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Back'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store', 0))).'\')',
                    'class' => 'back'
                    ))
                );
        } else {
            $this->setChild('back_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Close Window'),
                    'onclick'   => 'window.close()',
                    'class' => 'cancel'
                    ))
                );
        }

        if (!$this->getProduct()->isReadonly()) {
            $this->setChild('reset_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Reset'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/*/*', array('_current'=>true)).'\')'
                    ))
                );

            $this->setChild('save_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Save'),
                    'onclick'   => 'removeCheck();productForm.submit()',
                    'class' => 'save'
                    ))
                );
        }

        if (!$this->getRequest()->getParam('popup')) {
            if (!$this->getProduct()->isReadonly()) {
                $this->setChild('save_and_edit_button',
                    $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label'     => Mage::helper('catalog')->__('Save and Continue Edit'),
                        'onclick'   => 'removeCheck();saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
                        'class' => 'save'
                        ))
                    );
            }
            if ($this->getProduct()->isDeleteable()) {
                $this->setChild('delete_button',
                    $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label'     => Mage::helper('catalog')->__('Delete'),
                        'onclick'   => 'confirmSetLocation(\''.Mage::helper('catalog')->__('Are you sure?').'\', \''.$this->getDeleteUrl().'\')',
                        'class'  => 'delete'
                        ))
                    );
            }

            if ($this->getProduct()->isDuplicable()) {
                $this->setChild('duplicate_button',
                    $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label'     => Mage::helper('catalog')->__('Duplicate'),
                        'onclick'   => 'setLocation(\'' . $this->getDuplicateUrl() . '\')',
                        'class'  => 'add'
                        ))
                    );
            }
        }

      //  return parent::_prepareLayout();
    }

    public function skuAuto($type)
    { 
      $connection = Mage::getSingleton('core/resource')->getConnection('read');
       $table =  Mage::getSingleton('core/resource')->getTableName('skuautogenerate'); 

      $skuauto = $connection->query("SELECT skuautogenerate_id,appendstring , min_length FROM {$table} WHERE status = 1 AND product_type='{$type}'");
      $skuautores=$skuauto->fetch(); 
      if(!$skuautores["skuautogenerate_id"]){
       $skuauto = $connection->query("SELECT * FROM {$table} WHERE product_type='unique'");
       $skuautores = $skuauto->fetch();}
       return $skuautores;         

   }

public function getprevEntityId()
    { 
      $connection = Mage::getSingleton('core/resource')->getConnection('read');
      $table =  Mage::getSingleton('core/resource')->getTableName('catalog_product_entity'); 
       $sql = "SELECT entity_id FROM {$table} ORDER BY entity_id DESC LIMIT 1";
      $getprevEntityId=$connection->fetchOne($sql); 
       return $getprevEntityId;         
       
   }
   
}
