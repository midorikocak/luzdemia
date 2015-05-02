<?php

class Neev_Skuautogenerate_Block_Adminhtml_Skuautogenerate_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('skuautogenerateGrid');
      $this->setDefaultSort('skuautogenerate_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {  
      $collection = Mage::getModel('skuautogenerate/skuautogenerate')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('skuautogenerate_id', array(
          'header'    => Mage::helper('skuautogenerate')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'skuautogenerate_id',
      ));

      $this->addColumn('product_type', array(
          'header'    => Mage::helper('skuautogenerate')->__('Product Type'),
          'align'     =>'left',
          'index'     => 'product_type',
      ));
 $this->addColumn('suffixstring', array(
          'header'    => Mage::helper('skuautogenerate')->__('Sku suffix string'),
          'align'     =>'left',
          'index'     => 'suffixstring',
      ));
 $this->addColumn('prefixstring', array(
          'header'    => Mage::helper('skuautogenerate')->__('Sku prefix string'),
          'align'     =>'left',
          'index'     => 'prefixstring',
      ));
	 
      $this->addColumn('status', array(
          'header'    => Mage::helper('skuautogenerate')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('skuautogenerate')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('skuautogenerate')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('skuautogenerate')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('skuautogenerate')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('skuautogenerate_id');
        $this->getMassactionBlock()->setFormFieldName('skuautogenerate');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('skuautogenerate')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('skuautogenerate')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('skuautogenerate/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('skuautogenerate')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('skuautogenerate')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}