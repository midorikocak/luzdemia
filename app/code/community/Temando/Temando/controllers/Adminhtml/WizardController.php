<?php

class Temando_Temando_Adminhtml_WizardController extends Mage_Adminhtml_Controller_Action {

    const ERR_NO_SOAP = 'SOAP is not enabled on this server.  Please enable SOAP to use the Temando plugin.';

    /**
     * Adminhtml controller that redirects to the front end view and starts the wizard.
     * @return type
     */
    public function indexAction() {
        $params = array('id' => md5(Mage::getSingleton('core/session')->getSessionId()));
        if ($this->checkSoap()) {
            Mage::getSingleton('adminhtml/session')->addError(self::ERR_NO_SOAP);
            return $this->getResponse()->setRedirect($this->getRequest()->getServer('HTTP_REFERER'));
        } else {
            $this->_redirect('etemando/wizard/index', $params);
        }
    }

    /**
     * Checks to see if the SOAP extension is loaded
     * @return boolean
     */
    public function checkSoap() {
        if (!extension_loaded('soap')) {
            return true;
        } else {
            return false;
        }
    }

}

