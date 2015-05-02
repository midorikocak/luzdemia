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
class Toogas_Featuredpopup_Block_Adminhtml_Featuredpopup_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('featuredpopupGrid');
      $this->setDefaultSort('featuredpopup_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('featuredpopup/featuredpopup')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('featuredpopup_id', array(
          'header'    => Mage::helper('featuredpopup')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'featuredpopup_id',
      ));

      $this->addColumn('popup_name', array(
          'header'    => Mage::helper('featuredpopup')->__('Name'),
          'align'     =>'left',
          'index'     => 'popup_name',
      ));               	  	  
	  

      $this->addColumn('url_link', array(
          'header'    => Mage::helper('featuredpopup')->__('Link'),
          'align'     =>'left',
          'index'     => 'url_link',
      ));  
	  
      $this->addColumn('from_date', array(
          'header'    => Mage::helper('featuredpopup')->__('From:'),
          'type' => 'datetime',
          'index'     => 'from_date',
      )); 
      
      $this->addColumn('to_date', array(
          'header'    => Mage::helper('featuredpopup')->__('To:'),
          'type' => 'datetime',
          'index'     => 'to_date',
      ));     
	  
      $this->addColumn('is_active', array(
          'header'    => Mage::helper('featuredpopup')->__('Status'),
          'options' => $this->helper('featuredpopup/data')->sacaStatus(),
          'type' => 'options',
          'index'     => 'is_active',
      ));         
	  
      if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('featuredpopup')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('featuredpopup')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('featuredpopup')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
	  
      return parent::_prepareColumns();
  }



   protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }






    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('featuredpopup_id');
        $this->getMassactionBlock()->setFormFieldName('featuredpopup');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('featuredpopup')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('featuredpopup')->__('Are you sure?')
        ));

        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}