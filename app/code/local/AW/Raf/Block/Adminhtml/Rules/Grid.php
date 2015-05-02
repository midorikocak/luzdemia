<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Raf
 * @version    2.1.5
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Raf_Block_Adminhtml_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('RafRulesGrid');
        $this->setDefaultSort('rule_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    } 

    protected function _prepareCollection()
    {
        /** @var AW_Raf_Model_Mysql4_Rule_Collection $collection */
        $collection = Mage::getModel('awraf/rule')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('rule_id', array(
            'header'  => $this->__('Rule ID'),
            'align'   => 'right',
            'width'   => '50px',
            'index'   => 'rule_id'
        )); 

        $this->addColumn('rule_name', array(
            'header'  => $this->__('Rule Name'),
            'index'   => 'rule_name'
        ));

        $this->addColumn('type', array(
            'header'  => $this->__('Rule Type'),
            'index'   => 'type',
            'type'    => 'options',
            'width'   => '250px',
            'options' => Mage::getModel('awraf/source_ruleType')->toOptionArray()
        ));

        $this->addColumn('target', array(
            'header'  => $this->__('Rule Target'),
            'index'   => 'target',
            'type'    => 'number',
            'width'   => '50px',
        ));
        
        $this->addColumn('action_type', array(
            'header'  => $this->__('Discount Type'),
            'index'   => 'action_type',
            'type'    => 'options',
            'width'   => '150px',
            'options' => Mage::getModel('awraf/source_actionType')->toOptionArray()
        ));

        $this->addColumn('action_amount', array(
            'header'       => $this->__('Discount Amount'),
            'index'        => 'action',
            'filter_index' => 'action',
            'type'         => 'number',
            'width'        => '50px'
        ));

        $this->addColumn('stop_on_first', array(
            'header'  => $this->__('Stop Further Rules Processing'),
            'index'   => 'stop_on_first',
            'type'    => 'options',
            'width'   => '50px',
            'options' => array(
                $this->__('No'),
                $this->__('Yes')               
            ),
        ));

        $this->addColumn('priority', array(
            'header' => $this->__('Rule Priority'),
            'index'  => 'priority',
            'width'  => '50px',
            'type'   => 'number'
        ));

        $this->addColumn('active_from', array(
            'header'    => $this->__('Active From'),
            'index'     => 'active_from',
            'width'     => '150px',
            'type'      => 'datetime',
            'gmtoffset' => true,
            'default'   => ' ---- '
        ));

        $this->addColumn('status', array(
            'header'  => $this->__('Status'),
            'index'   => 'status',
            'type'    => 'options',
            'width'   => '70px',
            'options' => array(
                $this->__('Disabled'),
                $this->__('Enabled')
            ),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_ids', array(
                'header'                    => $this->__('Store View'),
                'index'                     => 'store_ids',
                'type'                      => 'store',
                'width'                     => '100px',
                'store_all'                 => true,
                'store_view'                => true,
                'sortable'                  => false,
                'renderer'                  => 'awraf/adminhtml_grid_renderer_multiStores',
                'filter_condition_callback' => array($this, 'filterStore'),
            ));
        }

        $this->addColumn('action',
            array(
                'header'     => $this->__('Action'),
                'width'      => '50px',
                'type'       => 'action',
                'getter'     => 'getRuleId',
                'actions'    => array(
                    array(
                        'caption'  => $this->__('Edit'),
                        'url'      => array(
                            'base' => '*/*/edit'
                        ),
                     'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true
            )
        );
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportXml', $this->__('XML'));

        return parent::_prepareColumns();
    }

    protected function filterStore($collection, $column)
    {
        $collection->addStoreFilter($column->getFilter()->getValue());
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('rule_id');
        $this->getMassactionBlock()->setFormFieldName('rules');

        $this->getMassactionBlock()->addItem('status', array(
            'label'=> $this->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('catalog')->__('Status'),
                    'values' => array(
                        $this->__('Disabled'),
                        $this->__('Enabled')
                    ),
                )
            )
        ));
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->__('Are you sure?')
        ));
    }

}
