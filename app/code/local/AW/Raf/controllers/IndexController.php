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

class AW_Raf_IndexController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        $_result = parent::preDispatch();
        if (!$this->_getSession()->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
        return $_result;
    }

    public function statsAction()
    {
        $this->loadLayout();
        $this->_initMessages();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Referred Friends'));
        $this->renderLayout();
    }

    protected function _initMessages()
    {
        $this->_initLayoutMessages('customer/session');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function inviteAction()
    {
        $this->_initMessages();
        $this->loadLayout('invite_ajax_form');
        $inviteBlock = $this->getLayout()->getBlock('awraf.invite');
        $inviteBlock->addData((array) $this->_getSession()->getFormData());
        $response = array(
                'content' => $inviteBlock->toHtml()
            )
        ;
        $response = Mage::helper('core')->jsonEncode($response);
        $this->getResponse()->setBody($response);
    }

    protected function _validate($invitePostData)
    {
        if (array_key_exists('email', $invitePostData)
            && !Zend_Validate::is(trim($invitePostData['email']), 'NotEmpty')) {
            $this->_getSession()->addError($this->__('Email field should not be empty'));
            return false;
        }

        if (array_key_exists('subject', $invitePostData)
            && !Zend_Validate::is(trim($invitePostData['subject']), 'NotEmpty')) {
            $this->_getSession()->addError($this->__('Subject field should not be empty'));
            return false;
        }
        if (array_key_exists('message', $invitePostData)
            && !Zend_Validate::is(trim($invitePostData['message']), 'NotEmpty')) {
            $this->_getSession()->addError($this->__('Message should not be empty'));
            return false;
        }        

        return true;
    }

    protected function _getValidatedEmails($emails)
    {
        $emails = array_unique(preg_split('/[\s;]/', $emails, -1, PREG_SPLIT_NO_EMPTY));

        if (empty($emails)) {
            $this->_getSession()->addError($this->__('Please enter at least on email address'));
            return false;
        }

        if (count($emails) > AW_Raf_Helper_Config::MAX_EMAILS_PER_LAUNCH) {
            $this->_getSession()->addError($this->__('Maximum number of emails per launch has been exceeded'));
            return false;
        }

        foreach ($emails as $email) {
            if (!Zend_Validate::is(trim($email), 'EmailAddress')) {
                $this->_getSession()->addError($this->__('Invalid email address %s', $email));
                return false;
            }

            $customerModel = Mage::getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsite()->getId())
                ->loadByEmail($email)
            ;

            if ($customerModel->getId()) {
                $this->_getSession()->addError($this->__('Email %s already a registered customer', $email));
                return false;
            }

            $refferalModel = Mage::getModel('awraf/referral')
                ->setWebsiteId(Mage::app()->getStore()->getWebsite()->getId())
                ->loadByEmail($email)
            ;

            if ($refferalModel->getId()) {
                $this->_getSession()->addError($this->__('Email to %s has been sent earlier', $email));
                return false;
            }
        }

        return $emails;
    }

    public function inviteSendnonAjaxAction()
    {
        $this->_inviteSend();
        $this->_redirectReferer();
        return $this;
    }

    protected function _inviteSend()
    {
        if (Mage::app()->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParam('invite', array());
            $this->_getSession()->setFormData($postData);

            if ($this->_validate($postData) && $validatedEmails = $this->_getValidatedEmails($postData['email'])) {
                $store = Mage::app()->getStore();

                foreach ($validatedEmails as $email) {
                    $referral = Mage::getModel('awraf/api')
                        ->createReferral(
                            array(
                                'referrer_id' => Mage::helper('awraf')->getCustomer()->getId(),
                                'website_id'  => $store->getWebsite()->getId(),
                                'store_id'    => $store->getId(),
                                'email'       => trim($email)
                            )
                        )
                    ;

                    $transport = new Varien_Object;
                    $invitation = new Varien_Object($postData);
                    $invitation
                        ->setInviteLink(
                            Mage::helper('awraf')->getReferrerLink($referral->getReferrerId(), $referral->getId())
                        )
                        ->setReferrerName(Mage::helper('awraf')->getCustomer()->getName())
                    ;
                    $transport
                        ->setEmailLaunch($email)
                        ->setStoreId($store->getId())
                        ->setParams(array('invitation' => $invitation))
                    ;

                    try {
                        Mage::helper('awraf/notifications')->send($transport);
                        $this->_getSession()->addSuccess($this->__('Email to %s has been successfully sent', $email));
                        $this->_getSession()->unsFormData();

                    } catch (Excetpion $e) {
                        Mage::logException($e);
                        $referral->delete();
                        $this->_getSession()->addError(
                            $this->__('Error on sending email notifications. Please contact store administrator')
                        );
                    }
                }
            }

        } else {
            $this->_getSession()->addError($this->__('Bad Request'));
        }
        return $this;
    }

    public function inviteSendAction()
    {
        $this->_inviteSend();
        $this->_forward('invite');
        return $this;
    }

    protected function _getEmailDuplicates($emails)
    {
        return Mage::getModel('awraf/referral')
            ->getCollection()
            ->addFieldToFilter('referrer_id', Mage::helper('awraf')->getCustomer()->getId())
            ->addFieldToFilter('email', $emails)
            ->addFieldToFilter('website_id', Mage::app()->getStore()->getWebsite()->getId())
            ->addFieldToFilter('customer_id', array('null' => true))
            ->load()
        ;
    }
}