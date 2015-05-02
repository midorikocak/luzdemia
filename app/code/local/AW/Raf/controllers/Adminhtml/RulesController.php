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


class AW_Raf_Adminhtml_RulesController extends Mage_Adminhtml_Controller_Action
{
    protected function _initRule()
    {
        $ruleModel = Mage::getModel('awraf/rule');
        $ruleId  = (int) $this->getRequest()->getParam('id');
        if ($ruleId) {
            try {
                $ruleModel->load($ruleId);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        if (null !== Mage::getSingleton('adminhtml/session')->getFormActionData()) {
            $ruleModel->addData(Mage::getSingleton('adminhtml/session')->getFormActionData());
            Mage::getSingleton('adminhtml/session')->setFormActionData(null);
        }
        Mage::register('awraf_rule', $ruleModel);

        return $ruleModel;
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('awraf/rules');

        return $this;
    }

    public function indexAction()
    {
        $this
            ->_title($this->__('Rules'))
        ;
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $rule = $this->_initRule();
        if ($rule->getId()) {
            $breadcrumbTitle = $breadcrumbLabel = $this->__('Edit Rule');
            $this
                ->_title($this->__('Edit Rule'))
            ;
        } else {
            $breadcrumbTitle = $breadcrumbLabel = $this->__('New Rule');
            $this
                ->_title($this->__('New Rule'))
            ;
        }

        $this
            ->_initAction()
            ->_addBreadcrumb($breadcrumbLabel, $breadcrumbTitle)
            ->renderLayout()
        ;
    }

    public function resetAllAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        $resource = Mage::getSingleton('core/resource');

        try {
            $connection = $resource->getConnection('core_write');
            $connection->query("DELETE FROM {$resource->getTableName('awraf/transaction')}");
            $connection->query("DELETE FROM {$resource->getTableName('awraf/discount')}");
            $connection->query("DELETE FROM {$resource->getTableName('awraf/referral')}");
            $connection->query("DELETE FROM {$resource->getTableName('awraf/trigger')}");
            $connection->query("DELETE FROM {$resource->getTableName('awraf/activity')}");
            $connection->query("DELETE FROM {$resource->getTableName('awraf/order_to_ref')}");
            $connection->query("DELETE FROM {$resource->getTableName('awraf/statistics')}");
            $session->addSuccess(Mage::helper('awraf')->__('Information has been successfully resetted'));
        } catch (Exception $e) {
            $session->addError($e->getMessage());
        }

        return $this->_redirectReferer();
    }

    public function saveAction()
    {
        $postObject =  new Varien_Object(
            $this->_filterDateTime($this->getRequest()->getParams(), array('active_from'))
        );

        try {
            $ruleModel = $this->_initRule();
            if (null !== $this->getRequest()->getParam('active_from', null)
                && !Zend_Date::isDate($postObject->getActiveFrom(), Varien_Date::DATETIME_INTERNAL_FORMAT)
            ) {
                $postObject->setActiveFrom(null);
                throw new Exception('"Active From" field value is not valid datetime format.');
            } else {
                $postObject->setActiveFrom(Mage::getSingleton('core/date')
                    ->gmtDate(null, $postObject->getActiveFrom())
                );
            }

            $ruleModel
                ->addData($postObject->getData())
                ->setActionType($postObject->getData('type_' . $postObject->getType()))
                ->save()
            ;
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Rule successfully saved'));
        } catch (Exception $e) {
            $postObject->setBack(true);
            Mage::getSingleton('adminhtml/session')
                ->addError($this->__($e->getMessage()))
                ->setFormActionData($postObject->getData())
            ;
        }

        if ($postObject->getBack()) {
            return $this->_redirect('*/*/edit', array('id' => $ruleModel->getId(), 'tab' => $postObject->getTab()));
        }
        return $this->_redirect('*/*/');
    }

    public function exportCsvAction()
    {
        $fileName = 'rules.csv';
        $content = $this->getLayout()->createBlock('awraf/adminhtml_rules_grid')
            ->getCsvFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'rules.xml';
        $content = $this->getLayout()->createBlock('awraf/adminhtml_rules_grid')
            ->getExcelFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function deleteAction()
    {
        try {
            $request = $this->getRequest()->getParams();
            if (!isset($request['id'])) {
                throw new Mage_Core_Exception($this->__('Incorrect rule id'));
            }
            $ruleModel = $this->_initRule();
            $ruleModel->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The rule is successfully removed'));
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
                throw new Mage_Core_Exception($this->__('Invalid rule ids'));
            }

            foreach ($ruleIds as $rule) {
                Mage::getSingleton('awraf/rule')->setId($rule)->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('%d rule(s) have been successfully deleted', count($ruleIds))
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        try {
            $ruleIds = $this->getRequest()->getParam('rules');
            $status = $this->getRequest()->getParam('status', null);
            if (!is_array($ruleIds)) {
                throw new Mage_Core_Exception($this->__('Invalid rule ids'));
            }

            if (null === $status) {
                throw new Mage_Core_Exception($this->__('Invalid status value'));
            }

            foreach ($ruleIds as $rule) {
                Mage::getSingleton('awraf/rule')->setId($rule)->setStatus($status)->save();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('%d rule(s) have been successfully updated', count($ruleIds))
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());

        if ($action == 'resetall') {
            return Mage::getSingleton('admin/session')->isAllowed('awraf/config/actions/resetall');
        } else {
            return Mage::getSingleton('admin/session')->isAllowed('awraf/rules');
        }
    }
}