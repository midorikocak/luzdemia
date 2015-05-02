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

class AW_Raf_Adminhtml_DiscountsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initDiscount()
    {
        $discountModel = Mage::getModel('awraf/rule_action_discount');
        $discountId  = (int) $this->getRequest()->getParam('id');
        if ($discountId) {
            try {
                $discountModel->load($discountId);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        Mage::register('awraf_discount', $discountModel);

        return $discountModel;
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('awraf/discounts');

        return $this;
    }

    public function indexAction()
    {
        $this
            ->_title($this->__('Discounts'))
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
        $this->_forward('edit');
    }

    public function editAction()
    {
        $discountModel = $this->_initDiscount();
        if ($discountModel->getId()) {
            $breadcrumbTitle = $breadcrumbLabel = $this->__('Edit Discount');
            $this
                ->_title($this->__('Discounts'))
                ->_title($this->__('Edit Discount'))
            ;
        } else {
            $breadcrumbTitle = $breadcrumbLabel = $this->__('New Discount');
            $this
                ->_title($this->__('Discounts'))
                ->_title($this->__('New Discount'))
            ;
        }

        $this
            ->_initAction()
            ->_addBreadcrumb($breadcrumbLabel, $breadcrumbTitle)
            ->renderLayout()
        ;
    }

    public function saveAction()
    {
        $request = new Varien_Object($this->getRequest()->getParams());
        $request->setType(AW_Raf_Model_Source_ActionType::PERCENT_DISCOUNT_VALUE);

        if (!is_null($request->getSelectedValues())) {             
            return $this->massDiscountAdd($request);
        }        
        try {
            $discountModel = $this->_initDiscount();
            $discountModel
                ->addData($request->getData())
                ->save()
            ;
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Rule successfully saved'));

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        if ($request->getBack()) {
            return $this->_redirect('*/*/edit', array('id' => $discountModel->getId(), 'tab' => $request->getTab()));
        }

        return $this->_redirect('*/*/');
    }

    public function massDiscountAdd($request)
    {       
        if (!$request->getSelectedValues()) {
             Mage::getSingleton('adminhtml/session')->addNotice($this->__('Please select at least one customer')); 
             Mage::getSingleton('adminhtml/session')->setFormData($request->getData());  

            return $this->_redirect('*/*/edit', array('id' => $request->getId(), 'tab' => $request->getTab()));
        }      
        
        $transport = new Varien_Object(
            array(
                'website_id' => $request->getRafWebsite(),
                'discount'   => $request->getDiscount(),
                'comment'    => $request->getComment(),
                'trigger'    => AW_Raf_Model_Source_ActionType::DISCOUNT_TRIGGER,
                'type'       => $request->getType()
            )
        );

        foreach (explode(',', $request->getSelectedValues()) as $val) {
            try {
                Mage::getModel('awraf/api')->add(
                    $transport
                        ->addData(
                            array(
                                 'customer_id' => $val,
                                 'created_at'  => Mage::getModel('core/date')->gmtDate()
                            )
                        )
                );
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Discounts Added'));

            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    $this->__('Some discounts were not added correctly. For more details see exceptions log')
                );
            }
        }

        return $this->_redirect('*/*/');
    }

    public function exportCsvAction()
    {
        $fileName = 'discounts.csv';
        $content = $this->getLayout()
            ->createBlock('awraf/adminhtml_bonus_grid')
            ->getCsvFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'discounts.xml';
        $content = $this->getLayout()
            ->createBlock('awraf/adminhtml_bonus_grid')
            ->getExcelFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function deleteAction()
    {
        try {
            $request = $this->getRequest()->getParams();
             
            if (!isset($request['id'])) {
                throw new Mage_Core_Exception($this->__('Incorrect discount rule id'));
            }

            $discountModel = $this->_initDiscount();
            $discountModel->delete();

            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The discount is successfully removed'));

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        return $this->_redirect('*/*/index');
    }

    public function massDeleteAction()
    {
        try {
            $ruleIds = $this->getRequest()->getParam('rules');

            if (!is_array($ruleIds)) {
                throw new Mage_Core_Exception($this->__('Invalid discount ids'));
            }

            foreach ($ruleIds as $rule) {
                Mage::getSingleton('awraf/rule_action_discount')->setId($rule)->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('%d rule(s) have been successfully deleted', count($ruleIds))
            );

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('awraf/discounts');
    }
}