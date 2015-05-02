<?php

class Temando_Temando_Block_Wizard_Html extends Mage_Install_Block_Abstract {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Creates HTML select box that outputs to the view
     * @param string $type
     * @return string
     */
    public function getHtmlSelect($type) {
        $select = $this->getSelectOptions($type);
        $html = $this->getLayout()->createBlock('core/html_select')
                ->setExtraParams($select['extraParams'])
                ->setName($select['name'])
                ->setId($select['id'])
                ->setTitle($select['title'])
                ->setClass($select['html_class'])
                ->setValue($select['value'])
                ->setOptions($select['options'])
                ->getHtml();
        return $html;
    }

    /**
     * Gets Temando Account mode options
     * @return array
     */
    public function modeOptions() {
        $mode = array(
            array(
                'value' => 0,
                'label' => Mage::helper('temando')->__('Live')
            ),
            array(
                'value' => 1,
                'label' => Mage::helper('temando')->__('Sandbox')
        ));
        return $mode;
    }

    /**
     * Gets Temando Fragile options 
     * @return array
     */
    public function yesNoOptions() {
        $yesno = array(
            array(
                'value' => 0,
                'label' => Mage::helper('temando')->__('No')
            ),
            array(
                'value' => 1,
                'label' => Mage::helper('temando')->__('Yes')
        ));
        return $yesno;
    }

    /**
     * Gets Temando Account Payment options
     * @return array
     */
    public function paymentOptions() {
        $payment = array(
            array(
                'value' => 'Credit',
                'label' => Mage::helper('temando')->__('Credit')
            ),
            array(
                'value' => 'Account',
                'label' => Mage::helper('temando')->__('Account')
        ));
        return $payment;
    }

    /**
     * Gets Allowed or Specific Country options
     * @return array
     */
    public function allowedCountryOptions() {
        $country = array(
            array(
                'value' => 0,
                'label' => Mage::helper('temando')->__('All Allowed Countries')
            ),
            array(
                'value' => 1,
                'label' => Mage::helper('temando')->__('Specific Countries')
        ));
        return $country;
    }

    /**
     * Gets Temando Rule Type options
     * @return string
     */
    public function ruleTypeOptions() {
        $options = Mage::getModel('temando/system_config_source_pricing')->getOptions();
        return $options;
    }

    /**
     * Gets the value of a name/key combination in the Temando Wizard session
     * @param string $name
     * @param string $key
     * @return null
     */
    public function getTmdSession($name, $key) {
        $session = Mage::getSingleton('core/session')->getData($name);
        if (is_array($session)) {
            if (array_key_exists($key, $session)) {
                return $session[$key];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Gets Temando Carrier Data
     * @param string $field
     * @return string
     */
    public function getCarrierData($field) {
        $path = 'carriers/temando/' . $field;
        return Mage::getStoreConfig($path);
    }
    
    /**
     * Gets Temando Carrier Data in Array
     * @param string $field
     * @return string
     */
    public function getCarrierDataArray($field) {
        $values = explode(',', Mage::getStoreConfig('carriers/temando/' . $field));
        return $values;
    }

    /**
     * Gets current admin url
     * @return string
     */
    public function getCancelUrl() {
        return Mage::getSingleton('core/session')->getTemandoCancelUrl();
    }

    /**
     * Gets options for rendering the select box based on type
     * @param string $type
     * @return array
     */
    protected function getSelectOptions($type) {
        $types = array(
            'mode' => array(
                'extraParams' => '',
                'name' => 'general_sandbox',
                'id' => 'sandbox',
                'title' => Mage::helper('temando')->__('Mode'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('general/sandbox'),
                'options' => $this->modeOptions()
            ),
            'payment' => array(
                'extraParams' => '',
                'name' => 'general_payment_type',
                'id' => 'payment_type',
                'title' => Mage::helper('temando')->__('Payment'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('general/payment_type'),
                'options' => $this->paymentOptions()
            ),
            'allowedCountries' => array(
                'extraParams' => '',
                'name' => 'temando_sallowspecific',
                'id' => 'sallowspecific',
                'title' => Mage::helper('temando')->__('Allow Specific Countries'),
                'html_class' => 'required-entry',
                'value' => $this->getCarrierData('sallowspecific'),
                'options' => $this->allowedCountryOptions()
            ),
            'specificCountries' => array(
                'extraParams' => 'multiple="multiple" size="10"',
                'name' => 'temando_specificcountry[]',
                'id' => 'specificcountry',
                'title' => Mage::helper('temando')->__('Specific Country'),
                'html_class' => 'required-entry multiselect',
                'value' => $this->getCarrierDataArray('specificcountry'),
                'options' => Mage::getModel('temando/system_config_source_country')->getOptions()
            ),
            'allowedCarriers' => array(
                'extraParams' => 'multiple="multiple" size="10"',
                'name' => 'temando_allowed_methods[]',
                'id' => 'allowed_methods',
                'title' => Mage::helper('temando')->__('Allowed Carriers'),
                'html_class' => 'required-entry multiselect',
                'value' => $this->getCarrierDataArray('allowed_methods'),
                'options' => Mage::getModel('temando/shipping_carrier_temando_source_method')->getOptions()
            ),
            'measureUnits' => array(
                'extraParams' => '',
                'name' => 'units_measure',
                'id' => 'measure',
                'title' => Mage::helper('temando')->__('Measure Unit'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('units/measure'),
                'options' => Mage::getModel('temando/system_config_source_unit_measure')->getOptions()
            ),
            'weightUnits' => array(
                'extraParams' => '',
                'name' => 'units_weight',
                'id' => 'weight',
                'title' => Mage::helper('temando')->__('Weight Unit'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('units/weight'),
                'options' => Mage::getModel('temando/system_config_source_unit_weight')->getOptions()
            ),
            'packagingType' => array(
                'extraParams' => '',
                'name' => 'defaults_packaging',
                'id' => 'packaging',
                'title' => Mage::helper('temando')->__('Packaging'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('defaults/packaging'),
                'options' => Mage::getModel('temando/system_config_source_shipment_packaging')->getOptions()
            ),
            'fragile' => array(
                'extraParams' => '',
                'name' => 'defaults_fragile',
                'id' => 'fragile',
                'title' => Mage::helper('temando')->__('Fragile'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('defaults/fragile'),
                'options' => $this->yesNoOptions()
            ),
            'countries' => array(
                'extraParams' => '',
                'name' => 'origin_country',
                'id' => 'origin_country',
                'title' => Mage::helper('temando')->__('Country'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('origin/country'),
                'options' => Mage::getModel('temando/system_config_source_country')->getOptions()
            ),
            'regions' => array(
                'extraParams' => '',
                'name' => 'origin_region',
                'id' => 'origin_region',
                'title' => Mage::helper('temando')->__('Region'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('origin/region'),
                'options' => Mage::getModel('temando/system_config_source_regions')->getOptions(true)
            ),
            'ruleType' => array(
                'extraParams' => '',
                'name' => 'pricing_method',
                'id' => 'pricing_method',
                'title' => Mage::helper('temando')->__('Rule Type'),
                'html_class' => 'required-entry',
                'value' => Mage::helper('temando')->getConfigData('pricing/method'),
                'options' => $this->ruleTypeOptions()
            ),
        );
        return $types[$type];
    }

}
