<?php

class Temando_Temando_Model_Wizard extends Mage_Core_Model_Abstract {

    const ERR_WHS_SYNC = 'An error occured when synchronizing with temando.com.  Origin location not saved on temando.com.';
    const SUC_WHS_SYNC = 'Origin location saved on temando.com.';

    public function _construct() {
        parent::_construct();
    }

    /**
     * Creates the cancel url from the current admin url
     */
    public function createCancelUrl() {
        $url = Mage::getBaseUrl() . Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
        Mage::getSingleton('core/session')->setTemandoCancelUrl($url);
    }

    /**
     * Checks the user details with the api
     * @param type $params
     * @return boolean
     */
    public function checkAccount($params) {
        try {
            $api = Mage::getModel('temando/api_client');
            /* @var $api Temando_Temando_Model_Api_Client */
            $api->connect(
                    $params['general_username'], $params['general_password'], $params['general_sandbox']);
            $result = $api->getLocations(array('clientId' => $params['general_client']));
            if (!$result) {
                Mage::getSingleton('core/session')->addError(self::ERR_NO_CONNECT);
                return false;
            }
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Saves Temando data in core resource
     * @param type $params
     * @return \Temando_Temando_Model_Wizard
     */
    public function _saveTemando($params) {
        foreach ($params as $key => $value) {
            if (strstr($key, '_', TRUE) != 'region') {
                $this->setTmdConfig('temando/' . $key, $value);
            }
        }

        return $this;
    }

    /**
     * Saves carrier data in core resource
     * @param type $params
     * @return \Temando_Temando_Model_Wizard
     */
    public function _saveCarrier($params) {
        foreach ($params as $key => $value) {
            if (strstr($key, '_', TRUE) != 'email') {
                $this->setTmdConfig('carriers/' . $key, $value);
            }
        }
        return $this;
    }

    /**
     * Sync origin data with Temando
     * @return \Temando_Temando_Model_Wizard
     */
    public function _syncOrigin() {

        try {
            $api = Mage::getModel('temando/api_client');
            $api->connect(
                    Mage::helper('temando')->getConfigData('general/username'), 
                    Mage::helper('temando')->getConfigData('general/password'), 
                    Mage::helper('temando')->getConfigData('general/sandbox')
            );

            //try to update 'Magento Warehouse'
            $magentoWarehouse = Mage::helper('temando')->getOriginRequestArray(new Varien_Object($this->getFieldsetData()));
            try {
                $api->updateLocation(array('location' => $magentoWarehouse));
            } catch (Exception $e) {
                try {
                    //if error updating location, location probably does not exist - try to create
                    Mage::log($e->getMessage());
                    $api->createLocation(array('location' => $magentoWarehouse));
                    Mage::getSingleton('core/session')->addSuccess(self::SUC_WHS_SYNC);
                } catch (Exception $e) {
                    Mage::getSingleton('core/session')->addError(self::ERR_WHS_SYNC);
                    Mage::log($e->getMessage());
                }
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }

        return $this;
    }

    /**
     * Sets parameters in Temando session
     * @param type $name
     * @param type $params
     * @return \Temando_Temando_Model_Wizard
     */
    public function setTmdSession($name, $params) {
        Mage::getSingleton('core/session')->setData($name, $params);
        return $this;
    }

    /**
     * Gets parameters from Temando session
     * @param type $name
     * @return type
     */
    protected function getTmdSession($name) {
        $session = Mage::getSingleton('core/session')->getData($name);
        return $session;
    }

    /**
     * Sets the current wizard step in the session
     * @param type $step
     * @return \Temando_Temando_Model_Wizard
     */
    public function setTmdStep($step) {
        Mage::getSingleton('core/session')->setTemandoWizardStep($step);
        return $this;
    }

    /**
     * Unsets step data from the session
     * @return \Temando_Temando_Model_Wizard
     */
    public function unsetTmdStep() {
        Mage::getSingleton('core/session')->unsetTemandoWizardStep();
        return $this;
    }

    /**
     * Saves data in core resource
     * @param type $key
     * @param type $value
     */
    protected function setTmdConfig($key, $value) {
        $path = strstr($key, '_', TRUE);
        $key = substr(strstr($key, '_'), 1);
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        Mage::getModel('core/config')->saveConfig($path . '/' . $key, $value);
    }

    /**
     * Sends an email if customer wants more information
     */
    public function sendEmail() {

        $email = $this->getTmdSession('temando_wizard_carriers');

        if (!empty($email['email_checkbox'])) {

            if (array_key_exists('email_checkbox', $email)) {

                $line = '--------------------------------';
                $body = 'Temando Carrier Enquiry Submitted<br>' . $line . '<br>';
                $body .= 'Name: ' . $email['email_full_name'] . '<br>';
                $body .= 'Email address: ' . $email['email_email_address'] . '<br>';
                $body .= 'Phone: ' . $email['email_phone'] . '<br><br>';
                $body .= 'Current Magento Details<br>' . $line . '<br>';
                $body .= 'Client ID: ' . Mage::helper('temando')->getConfigData('general/client') . '<br>';
                $body .= 'Login ID: ' . Mage::helper('temando')->getConfigData('general/username') . '<br>';
                $body .= 'Payment Type: ' . Mage::helper('temando')->getConfigData('general/payment_type') . '<br>';

                try {
                    $mail = new Zend_Mail();
                    $mail->setFrom("temando_bronze@temando.com", "Temando Bronze - Starter");
                    $mail->addTo("sales@temando.com", "Sales");
                    $mail->setSubject("Temando Carrier Enquiry");
                    $mail->setBodyHtml($body);
                    $mail->send();
                    Mage::getSingleton('core/session')->unsetData('temando_wizard_carriers');
                } catch (Exception $e) {
                    //echo $e->getMessage();
                }
            }
        }
        return $this;
    }

}
