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

class AW_Raf_Adminhtml_StatsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('awraf/stats');

        return $this;
    }

    public function indexAction()
    {
        $this
            ->_title($this->__('Statistics'))
        ;
        $this->_initAction();
        $this->renderLayout();
    }    

    public function exportCsvAction()
    {
        $fileName = 'statistics.csv';
        $content = $this->getLayout()
            ->createBlock('awraf/adminhtml_stats_grid')
            ->getCsvFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'statistics.xml';
        $content = $this->getLayout()
            ->createBlock('awraf/adminhtml_stats_grid')
            ->getExcelFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('awraf/stats');
    }
}