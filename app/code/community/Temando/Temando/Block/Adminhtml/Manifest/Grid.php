<?php

class Temando_Temando_Block_Adminhtml_Manifest_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('temando/manifest')->getCollection();
        /* @var $collection Temando_Temando_Model_Manifest */
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('created_at', array(
            'header' => Mage::helper('temando')->__('Created At'),
        	'width' => '100px',
        	'type' => 'datetime',
            'index' => 'created_at',
        ));
        
        $this->addColumn('location_id', array(
            'header' => Mage::helper('temando')->__('Location'),
            'index' => 'location_id',
            'type'  => 'text',
            'width' => '200px',
        ));
        
        $this->addColumn('carrier_id', array(
            'header' => Mage::helper('temando')->__('Carrier'),
            'index' => 'carrier_id',
            'type'  => 'options',
            'width' => '100px',
            'options' => Mage::getModel('temando/shipping_carrier_temando_source_method')->getOptions(),
        ));

        $this->addColumn('start_date', array(
            'header' => Mage::helper('temando')->__('Date'),
        	'width' => '100px',
        	'type'  => 'date',
            'index' => 'start_date',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('temando')->__('Type'),
            'index' => 'type',
            'type'  => 'options',
            'width' => '100px',
            'options' => array('Awaiting Confirmation' => 'Awaiting Confirmation', 'Confirmed' => 'Confirmed'),
        ));

        $this->addColumn('action_man',
            array(
                'header'    =>  Mage::helper('temando')->__('Manifest Document'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('temando')->__('Download'),
                        'url'       => array('base'=> '*/*/manifest'),
                        'field'     => 'id'
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
        ));

        $this->addColumn('action_lab',
            array(
                'header'    =>  Mage::helper('temando')->__('Label Document'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('temando')->__('Download'),
                        'url'       => array('base'=> '*/*/label'),
                        'field'     => 'id'
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
    }

    protected function _prepareMassaction___()
    {
        $this->setMassactionIdField('manifest_id');
        $this->getMassactionBlock()->setFormFieldName('manifest');

        $this->getMassactionBlock()->addItem('confirm', array(
             'label'=> Mage::helper('catalog')->__('Confirm'),
             'url'  => $this->getUrl('*/*/massConfirm'),
             'confirm' => Mage::helper('temando')->__('Are you sure?')
        ));
    }

    /**
     * Prepare grid massaction column
     *
     * @return unknown
     */
    protected function _prepareMassactionColumn()
    {
        $columnId = 'massaction';
        $massactionColumn = $this->getLayout()->createBlock('adminhtml/widget_grid_column')
                ->setData(array(
                    'index'     => $this->getMassactionIdField(),
                    'type'      => 'massaction',
                    'name'      => $this->getMassactionBlock()->getFormFieldName(),
                    'align'     => 'center',
                    'is_system' => true,
                    'renderer'  => 'temando/adminhtml_manifest_grid_renderer_checkbox',
                ));

        if ($this->getNoFilterMassactionColumn()) {
            $massactionColumn->setData('filter', false);
        }

        $massactionColumn->setSelected($this->getMassactionBlock()->getSelected())
            ->setGrid($this)
            ->setId($columnId);

        $oldColumns = $this->_columns;
        $this->_columns = array();
        $this->_columns[$columnId] = $massactionColumn;
        $this->_columns = array_merge($this->_columns, $oldColumns);
        return $this;
    }

}
