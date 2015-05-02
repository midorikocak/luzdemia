<?php

include_once('Mage/Newsletter/controllers/SubscriberController.php');

class ES_Newssubscribers_SubscriberController extends Mage_Newsletter_SubscriberController
{

    public function newAction()
    {
        parent::newAction();

        if (!Mage::getStoreConfig('newsletter/common/isactive'))
            return '';

        $session = Mage::getSingleton('core/session');
        $errorMsg = '';
        $errors = $session->getMessages(false)->getErrors();
        $email = (string) $this->getRequest()->getPost('email');
        if ($errors)
            $errorMsg = $errors[0]->getText();

        if (!$errorMsg) {
            try {

                /*$mailTemplate = Mage::getModel('core/email_template');
                $mailTemplate->sendTransactional(1, array(
                    'name' => Mage::getStoreConfig('trans_email/ident_general/name'),
                    'email' => Mage::getStoreConfig('trans_email/ident_general/email')
                ), $email, 'newsletter_subscr_coupon', array(
                    'couponCode' => $couponData['code']
                ));*/

            }
            catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
            }
            catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription.'));
            }
        }
    }

    public function newajaxAction()
    {
        $this->newAction();
        $session = Mage::getSingleton('core/session');
        $messages = $session->getMessages(true);
        $errors = $messages->getErrors();
        $response = array(
            'errorMsg' => '',
            'successMsg' => ''
        );

        if ($errors) {
            $response['errorMsg'] = $errors[0]->getText();
        } else {
            $success = $messages->getItemsByType('success');
            $response['successMsg'] = $success[0]->getText();
        }

        echo Mage::helper('core')->jsonEncode($response);
        exit;
    }
}
