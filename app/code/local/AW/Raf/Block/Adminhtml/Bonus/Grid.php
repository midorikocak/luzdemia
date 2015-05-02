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


class AW_Raf_Block_Adminhtml_Bonus_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();       
        $this->setId('RafBonusGrid');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var AW_Raf_Model_Mysql4_Rule_Action_Discount_Collection $collection */
        $collection = Mage::getModel('awraf/rule_action_discount')->getCollection();
        $collection
            ->joinCustomerTable()
            ->joinRafRuleTable()
        ;
        $this->setCollection($collection);
 
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('item_id', array(
            'header' => $this->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'item_id',
        ));

        $this->addColumn('email', array(
            'header' => $this->__('Customer Email'),              
            'filter_index' => 'customers.email',
            'index' => 'email',
        ));

       if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website_id',
                array(
                    'header'=> $this->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'website_id',
                    'type'      => 'options',
                    'filter_index' => 'main_table.website_id',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }
        
        $this->addColumn('discount', array(
            'header' => $this->__('Discount Amount, %'),
            'index' => 'discount',
            'type'  => 'number'           
        ));

        $this->addColumn('trig_qty', array(
            'type' => 'number',
            'header' => $this->__('Trigger Count'),
            'index' => 'trig_qty'            
        ));
        
        $this->addColumn('rule_name', array(
            'header' => $this->__('Related To Rule'),
            'index' => 'rule_name',
            'is_system' => true,
            'renderer' => 'awraf/adminhtml_grid_renderer_ruleName',
            'default' => '--',            
            'filter_index' => 'rules.rule_name'        
        ));
        
         $this->addColumn('comment',
            array(
                'header'=> $this->__('Comment'),               
                'index' => 'comment',
                'type' => 'text', 
                'truncate' => 100,
                'default' => '--',
                'width' => '550px'
        )); 
   
 
        $this->addColumn('created_at', array(
            'header' => $this->__('Created At'),
            'index' => 'created_at',
            'width' => '170px',
            'type' => 'datetime',
            'filter_index' => 'main_table.created_at',
            'gmtoffset' => true,
            'default' => ' ---- '
        ));
 
        $this->addColumn('action',
            array(
                'header'    => $this->__('Action'),
                'width'     => '150px',
                'type'      => 'action',               
                'getter'     => 'getItemId',
                'actions'   => array(
                    array(
                        'caption' => $this->__('Edit'),
                        'url'     => array(
                            'base'=>'*/*/edit'                           
                        ),
                        'field'   => 'id'
                    ),
                    array(
                        'caption' => $this->__('Delete'),
                        'url'     => array(
                            'base'=>'*/*/delete'                           
                        ),
                        'field'   => 'id',
                        'confirm' => $this->__('Are you sure?')
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true
        ));
        
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportXml', $this->__('XML'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('rules');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->__('Are you sure?')
        ));
    }

}
