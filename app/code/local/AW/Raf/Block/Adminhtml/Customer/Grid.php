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

class AW_Raf_Block_Adminhtml_Customer_Grid extends Mage_Adminhtml_Block_Customer_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_fieldset');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $actions = array();
        foreach ($this->getColumn('action')->getActions() as $actionItem) {
            if (isset($actionItem['url']) && isset($actionItem['url']['base'])) {
                $actionItem['url']['base'] = str_replace('*/*/', 'adminhtml/customer/', $actionItem['url']['base']);
            }
            array_push($actions, $actionItem);
        }
        $this->getColumn('action')->setActions($actions);
        return $this;
    }

    public function addExportType($url, $label)
    {
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/customersGrid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return null;
    }
}