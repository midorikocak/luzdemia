<?php
class IWD_All_Block_Adminhtml_Conflicts_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('iwd_extensions_conflicts_grid');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('iwdall/conflicts')->getRewritesCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('class',
            array(
                'header' => Mage::helper('iwdall')->__('Base Class'),
                'index' => 'class',
                'filter_condition_callback' => array($this, '_classFilter'),
            ));

        $this->addColumn('rewrites',
            array(
                'header' => Mage::helper('iwdall')->__('Rewrites'),
                'index' => 'rewrites',
                'sortable' => false,
                'filter_condition_callback' => array($this, '_rewritesFilter'),
                'renderer' => 'iwdall/adminhtml_conflicts_grid_renderer_rewrites',
            ));

        $this->addColumn('type',
            array(
                'header' => Mage::helper('iwdall')->__('Type'),
                'index' => 'type',
                'type' => 'options',
                'options' => Mage::getModel('iwdall/conflicts')->getTypes(),
            ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($item)
    {
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _classFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->addFieldToFilter("class",  array("like" => $value));

        return $this;
    }

    protected function _rewritesFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $this->getCollection()->addFieldToFilter("rewrites", array("like" => $value));

        return $this;
    }
}
