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


class AW_Raf_Block_Adminhtml_Stats_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('RafStatisticsGrid');
        $this->setDefaultSort('customer_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var AW_Raf_Model_Mysql4_Statistics_Collection $collection */
        $collection = Mage::getModel('awraf/statistics')->getCollection();
        $collection->joinCustomerTable();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    } 

    protected function _prepareColumns()
    {        
        $this->addColumn('customer_id', array(
            'header'    => $this->__('Customer Id'),
            'width'     => '50px',
            'index'     => 'customer_id',
            'type'  => 'number',
        ));
        
        $this->addColumn('email', array(
            'header' => $this->__('Customer Email'),
            'index' => 'email'
        ));
        
        $this->addColumn('referrals_number', array(
            'header' => $this->__('Number of Registered Referrals'),
            'index' => 'referrals_number',            
            'type'  => 'number'             
        ));   
        
        $this->addColumn('qty_purchased', array(
            'header' => $this->__('Qty Purchased By Referrals'),
            'index' => 'qty_purchased',
            'type'  => 'number'         
        ));
        
        $this->addColumn('amount_purchased', array(
            'header' => $this->__('Total Amount Purchased By Referrals'),
            'index' => 'amount_purchased',
            'type'  => 'price',
            'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode()           
        ));
        
        $this->addColumn('earned', array(
            'header' => $this->__('Actual Balance Amount'),
            'index' => 'earned',
            'type'  => 'price',
            'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode()          
        ));
        
         $this->addColumn('spent', array(
            'header' => $this->__('Total Money Spent By Customer'),
            'index' => 'spent',
            'type'  => 'price',
            'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode()           
        )); 
        
         
        $this->addColumn('stats_update', array(
            'header' => $this->__('Updated At'),
            'index' => 'stats_update',     
            'width' => '170px',
            'type' => 'datetime',
            'gmtoffset' => true,
            'default' => ' ---- '
        ));  
        
        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportXml', $this->__('XML')); 

        return parent::_prepareColumns();
    } 
    
    public function getRowUrl($row)
    {
        return null;
    }
 
}
