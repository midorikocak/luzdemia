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

class AW_Raf_Adminhtml_TransactionsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initTransaction()
    {
        $transactionModel = Mage::getModel('awraf/rule_action_transaction');
        $transactionId  = (int) $this->getRequest()->getParam('id');
        if ($transactionId) {
            try {
                $transactionModel->load($transactionId);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        Mage::register('awraf_rule', $transactionModel);

        return $transactionModel;
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('awraf/transactions');

        return $this;
    }

    public function indexAction()
    {
        $this
            ->_title($this->__('Transactions'))
        ;
        $this->_initAction();
        $this->renderLayout();
    }

    public function customersGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $breadcrumbTitle = $breadcrumbLabel = $this->__('Create Transaction');
        $this
            ->_title($this->__('Create Transaction'))
            ->_initAction()
            ->_addBreadcrumb($breadcrumbLabel, $breadcrumbTitle)
            ->renderLayout()
        ;
    }

    public function saveAction()
    {
        $postObject =  new Varien_Object($this->getRequest()->getParams());
        if (!is_null($postObject->getSelectedValues())) {
            return $this->_massDiscountAdd($postObject);
        }

        try {
            $transactionModel = $this->_initTransaction()
                ->setWebsiteId($postObject->getRafWebsite())
                ->addData($postObject->getData())
                ->save()
            ;
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Transaction successfully saved'));

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        if ($postObject->getBack()) {
            return $this->_redirect(
                '*/*/edit',
                array('id' => $transactionModel->getId(), 'tab' => $postObject->getTab())
            );
        }

        return $this->_redirect('*/*/');
    }

    protected function _massDiscountAdd(Varien_Object $postObject)
    {        
        if (!$postObject->getSelectedValues()) {
             Mage::getSingleton('adminhtml/session')->addNotice($this->__('Please select at least one customer')); 
             Mage::getSingleton('adminhtml/session')->setFormData($postObject->getData());
             return $this->_redirect('*/*/new');
        } 
        
        $transport = new Varien_Object(array(
            'website_id' => $postObject->getRafWebsite(),
            'discount'   => $postObject->getDiscount(),
            'comment'    => $postObject->getComment(),
            'trigger'    => AW_Raf_Model_Source_ActionType::TRANSACTION_TRIGGER,
            'type'       => AW_Raf_Model_Source_ActionType::FIXED_DISCOUNT_VALUE
        ));

        try {
            foreach (explode(',', $postObject->getSelectedValues()) as $val) {
                Mage::getModel('awraf/api')->add(
                    $transport
                        ->setData('customer_id', $val)
                        ->setData('created_at', Mage::getModel('core/date')->gmtDate())
                );
            }

            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Transactions Added'));

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
        }

        return $this->_redirect('*/*/');
    }

    public function exportCsvAction()
    {
        $fileName = 'transactions.csv';
        $content = $this->getLayout()
            ->createBlock('awraf/adminhtml_transaction_grid')
            ->getCsvFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'discounts.xml';
        $content = $this->getLayout()
            ->createBlock('awraf/adminhtml_transaction_grid')
            ->getExcelFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('awraf/transactions');
    }
}