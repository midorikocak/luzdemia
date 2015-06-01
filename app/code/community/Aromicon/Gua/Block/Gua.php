<?php
class Aromicon_Gua_Block_Gua extends Mage_Core_Block_Template
{
    public $_order;

    public function getAccountId()
    {
        return Mage::getStoreConfig('aromicon_gua/general/account_id');
    }

    public function isAnonymizeIp()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/general/anonymize_ip') ? 'true' : 'false';
    }

    public function isActive()
    {
        if(Mage::getStoreConfigFlag('aromicon_gua/general/enable')
            && Mage::getStoreConfig('aromicon_gua/general/add_to') == $this->getParentBlock()->getNameInLayout()){
                return true;
        }
        return false;
    }

    public function isEcommerce()
    {
        $successPath =  Mage::getStoreConfig('aromicon_gua/ecommerce/success_url') != "" ? Mage::getStoreConfig('aromicon_gua/ecommerce/success_url') : '/checkout/onepage/success';
        if(Mage::getStoreConfigFlag('aromicon_gua/ecommerce/enable')
            && strpos($this->getRequest()->getPathInfo(), $successPath) !== false){
                return true;
        }
        return false;
    }

    public function isCheckout()
    {
        $checkoutPath =  Mage::getStoreConfig('aromicon_gua/ecommerce/checkout_url') != "" ?  Mage::getStoreConfig('aromicon_gua/ecommerce/checkout_url') : '/checkout/onepage';
        if(Mage::getStoreConfigFlag('aromicon_gua/ecommerce/funnel_enable')
            && strpos($this->getRequest()->getPathInfo(), $checkoutPath) !== false){
            return true;
        }
        return false;
    }

    public function getCheckoutUrl()
    {
       return Mage::getStoreConfig('aromicon_gua/ecommerce/checkout_url') != "" ?  Mage::getStoreConfig('aromicon_gua/ecommerce/checkout_url') : '/checkout/onepage';
    }

    public function getActiveStep()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn() ? 'billing' : 'login';
    }

    public function isSSL()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/general/force_ssl');
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if(!isset($this->_order)){
            $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            $this->_order = Mage::getModel('sales/order')->load($orderId);
        }
        return $this->_order;
    }

    public function getTransactionIdField()
    {
        return Mage::getStoreConfig('aromicon_gua/ecommerce/transaction_id') != false ? Mage::getStoreConfig('aromicon_gua/ecommerce/transaction_id') : 'entity_id';
    }

    public function isCustomerGroup()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/customer/enable_customergroup') && $this->getCustomerGroupDimensionId() != '';
    }

    public function getCustomerGroupDimensionId()
    {
        return Mage::getStoreConfig('aromicon_gua/customer/dimension_customergroup');
    }

    public function getCustomerGroup()
    {
        $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        return Mage::getModel('customer/group')->load($groupId)->getCode();
    }

    public function isFirstPurchase()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/customer/enable_first_order') && $this->getFirstPurchaseDimensionId() !='';
    }

    public function getFirstPurchaseDimensionId()
    {
        return Mage::getStoreConfig('aromicon_gua/customer/dimension_first_purchase');
    }

    public function isNumberOfPurchase()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/customer/enable_customer_orders') && $this->getNumberOfPurchaseMetricId() !='';
    }

    public function getNumberOfPurchaseMetricId()
    {
        return Mage::getStoreConfig('aromicon_gua/customer/metric_customer_orders');
    }

    public function getNumberOfOrders()
    {
        return Mage::getResourceModel('sale/order_collection')
            ->addFieldToFilter('customer_email', array('eq' => $this->getOrder()->getCustomerEmail()))
            ->getSize();
    }

    public function isRemarketing()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/remarketing/enable');
    }

    public function isPriceTracking()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/product/enable_price') && $this->getPriceMetricId() !='';
    }

    public function getPriceMetricId()
    {
        return Mage::getStoreConfig('aromicon_gua/product/metric_price');
    }

    public function isAvailabilityTracking()
    {
        return Mage::getStoreConfigFlag('aromicon_gua/product/enable_availability') && Mage::getStoreConfig('aromicon_gua/product/dimension_availability') != '';
    }

    public function getAvailabilityDimensionId()
    {
        return Mage::getStoreConfig('aromicon_gua/product/dimension_availability');
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }
}