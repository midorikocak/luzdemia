<?php

class Temando_Temando_Block_Adminhtml_Shipment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setSaveParametersInSession(true);
    }
    
    protected function _prepareLayout() {
        parent::_prepareLayout();
        $this->setChild('batch_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('adminhtml')->__('Batch Booking'),
                            'onclick' => 'return false',
                            'class' => 'disabled',
                            'title' => 'Available in the Business Extension'
                        ))
        );
        return $this;
    }

    public function getBatchButtonHtml() {
        return $this->getChildHtml('batch_button');
    }

    public function getMainButtonsHtml() {
        $html = '';
        if ($this->getFilterVisibility()) {
            $html.= $this->getBatchButtonHtml();
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;
    }

    protected function _prepareCollection()
    {
	$collection = Mage::getModel('temando/shipment')->getCollection();
        $collection->join('sales/order', 'main_table.order_id=`sales/order`.entity_id', array('increment_id', 'created_at', 'shipping_amount'));
        /* @var $collection Temando_Temando_Model_Shipment */
	
	$collection->getSelect()->order(array(
	    'main_table.status ASC',
	    'created_at DESC'
	));
	
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('order_number', array(
            'header' => Mage::helper('temando')->__('Order #'),
	    'width' => '100px',
            'index' => 'increment_id',
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('temando')->__('Purchased On'),
	    'width' => '160px',
	    'type' => 'datetime',
            'index' => 'created_at',
	    'filter_index' => '`sales/order`.created_at',
        ));
        
        $this->addColumn('status', array(
            'header' => Mage::helper('temando')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '100px',
            'options' => Mage::getSingleton('temando/system_config_source_shipment_status')->getOptions(),
	    'filter_index' => 'main_table.status',
        ));
        
        $this->addColumn('anticipated_cost', array(
            'header' => Mage::helper('temando')->__('Anticipated Cost'),
            'align' => 'left',
	    'type'  => 'currency',
	    'currency_code' => 'AUD',
            'index' => 'anticipated_cost',
        ));
        
        $this->addColumn('shipping_paid', array(
            'header' => Mage::helper('temando')->__('Shipping Paid'),
            'align' => 'left',
	    'type'  => 'currency',
	    'currency_code' => Mage::app()->getStore()->getCurrentCurrencyCode(),
            'index' => 'shipping_amount',
        ));

        $this->addColumn('selected_quote_description', array(
            'header' => Mage::helper('temando')->__('Customer Selected Quote'),
            'align' => 'left',
            'index' => 'customer_selected_quote_description',
        ));
	
        $this->addColumn('action', array(
            'header' => Mage::helper('temando')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                'view' => array(
                    'caption' => Mage::helper('temando')->__('View'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }
   

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
