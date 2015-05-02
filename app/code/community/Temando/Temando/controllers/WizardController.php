<?php

class Temando_Temando_WizardController extends Mage_Core_Controller_Front_Action {

    const ERR_ORIGIN_EXISTS = 'This warehouse already exists.  Please select another name.';
    const ERR_NO_CONNECT = 'Cannot connect to the api. Please check your connection and try again.';

    public function construct() {
        parent:: construct();

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Main index
     */
    public function indexAction() {
        $params = $this->getRequest()->getParams();
        if ($params) {
            if ($params['key'] && $params['id'] == md5(Mage::getSingleton('admin/session')->getCookie()->get('adminhtml'))) {
                Mage::getModel('temando/wizard')->setTmdStep(1);
                Mage::getModel('temando/wizard')->createCancelUrl();
                $this->_redirect('*/*/index');
            }
        }
        $step = Mage::getSingleton('core/session')->getTemandoWizardStep();
        $this->setControllerLayout($step);
    }

    /**
     * Save action to save data
     */
    public function saveAction() {
        $params = $this->getRequest()->getPost();
        $step = (Mage::getSingleton('core/session')->getTemandoWizardStep());
        $wizard = Mage::getModel('temando/wizard');
        /* @var $wizard Temando_Temando_Model_Wizard */

        if ($step == Temando_Temando_Model_System_Config_Source_Wizard::ACCOUNT) {
            if (!$wizard->checkAccount($params)) {
                $step = 0;
                $wizard->_saveTemando($params);
            };
        }
        if ($step == Temando_Temando_Model_System_Config_Source_Wizard::CARRIERS) {
            $wizard->setTmdSession('temando_wizard_carriers', $params);
        }
        if ($step == Temando_Temando_Model_System_Config_Source_Wizard::ACCOUNT || $step == Temando_Temando_Model_System_Config_Source_Wizard::CATALOG_PRODUCT) {
            $wizard->_saveTemando($params);
        } else if ($step == Temando_Temando_Model_System_Config_Source_Wizard::CARRIERS) {
            $wizard->_saveCarrier($params);
        } else if ($step == Temando_Temando_Model_System_Config_Source_Wizard::ORIGIN) {
            $wizard->_saveTemando($params)->_syncOrigin();
        } else if ($step == Temando_Temando_Model_System_Config_Source_Wizard::RULE) {
            $wizard->_saveTemando($params)->sendEmail();
        }
        Mage::getSingleton('core/session')->setTemandoWizardStep($step + 1);
        Mage::app()->cleanCache(array('CONFIG'));
        $this->_redirect('*/*/index');
    }

    /**
     * Goes back to the previous step
     */
    public function backAction() {
        $step = (Mage::getSingleton('core/session')->getTemandoWizardStep() - 1);
        Mage::getSingleton('core/session')->setTemandoWizardStep($step);
        $this->_redirect('*/*/index');
    }

    /**
     * Skips the save function and goes to the next step
     */
    public function skipAction() {
        $step = (Mage::getSingleton('core/session')->getTemandoWizardStep() + 1);
        if ($step == Temando_Temando_Model_System_Config_Source_Wizard::END) {
            Mage::getModel('temando/wizard')->sendEmail();
        }
        Mage::getSingleton('core/session')->setTemandoWizardStep($step);
        $this->_redirect('*/*/index');
    }

    /**
     * Sets the layout based on the current step saved in the session
     * @param type $step
     */
    protected function setControllerLayout($step) {

        $wizard = Mage::getModel('temando/system_config_source_wizard');
        /* @var $wizard Temando_Temando_Model_System_Config_Source_Wizard */

        if ($step) {

            $this->loadLayout();
            if ($step == Temando_Temando_Model_System_Config_Source_Wizard::ACCOUNT) {
                $this->getLayout()->getBlock('root')->getChild('content')->setTemplate($wizard->getOptionLabel(Temando_Temando_Model_System_Config_Source_Wizard::ACCOUNT));
            } elseif ($step == Temando_Temando_Model_System_Config_Source_Wizard::CARRIERS) {
                $this->getLayout()->getBlock('root')->getChild('content')->setTemplate($wizard->getOptionLabel(Temando_Temando_Model_System_Config_Source_Wizard::CARRIERS));
            } elseif ($step == Temando_Temando_Model_System_Config_Source_Wizard::CATALOG_PRODUCT) {
                $this->getLayout()->getBlock('root')->getChild('content')->setTemplate($wizard->getOptionLabel(Temando_Temando_Model_System_Config_Source_Wizard::CATALOG_PRODUCT));
            } elseif ($step == Temando_Temando_Model_System_Config_Source_Wizard::ORIGIN) {
                $this->getLayout()->getBlock('root')->getChild('content')->setTemplate($wizard->getOptionLabel(Temando_Temando_Model_System_Config_Source_Wizard::ORIGIN));
            } elseif ($step == Temando_Temando_Model_System_Config_Source_Wizard::RULE) {
                $this->getLayout()->getBlock('root')->getChild('content')->setTemplate($wizard->getOptionLabel(Temando_Temando_Model_System_Config_Source_Wizard::RULE));
            } else {
                $this->getLayout()->getBlock('root')->getChild('content')->setTemplate($wizard->getOptionLabel(Temando_Temando_Model_System_Config_Source_Wizard::END));
                Mage::getModel('temando/wizard')->unsetTmdStep();
            }
            $this->renderLayout();
        }
    }

}
