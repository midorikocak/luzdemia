<?php


class Magik_Deziresettings_Adminhtml_RestoreController extends Mage_Adminhtml_Controller_Action
{

    protected $_stores;
    protected $_clear;

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('magik/dezire/restore')
            ->_addBreadcrumb(Mage::helper('deziresettings')->__('Restore Defaults'), Mage::helper('deziresettings')->__('Restore Defaults'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->_title($this->__('Magik'))
            ->_title($this->__('Dezire'))
            ->_title($this->__('Restore Defaults'));

        $this->_addContent($this->getLayout()->createBlock('deziresettings/adminhtml_restore_edit'));
        $block = $this->getLayout()->createBlock('core/text', 'restore-desc')
                ->setText('<b>Theme default settings :</b>
                        <br/><br/>
                        <b>Appearance</b>
                        <ul>
                            <li>ATTENTION: All colors will be restored to default scheme. Do not restore if you do not want to loose your changes</li>
                        </ul>
                        ');
        $this->_addLeft($block);

        $this->renderLayout();
    }

    public function restoreAction()
    {
        $this->_stores = $this->getRequest()->getParam('stores', array(0));
        $this->_clear = $this->getRequest()->getParam('clear_scope', false);
	    $setup_cms = $this->getRequest()->getParam('setup_cms', 0);

        if ($this->_clear) {
            if ( !in_array(0, $this->_stores) )
                $stores[] = 0;
        }

	    try { 
		    $defaults = new Varien_Simplexml_Config();
            $defaults->loadFile(Mage::getBaseDir().'/app/code/local/Magik/Deziresettings/etc/config.xml');
            $this->_restoreSettings($defaults->getNode('default/deziresettings')->children(), 'deziresettings');

		    if ($setup_cms) {
                Mage::getModel('deziresettings/settings')->setupCms();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('deziresettings')->__('Dezire Theme Settings has been restored. Please clear cache (System > Cache management) if you do not see changes in storefront'));
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('deziresettings')->__('An error occurred while restoring theme settings.'));
        }

        $this->getResponse()->setRedirect($this->getUrl("*/*/"));
    }

    private function _restoreSettings($items, $path)
    {
        $websites = Mage::app()->getWebsites();
        $stores = Mage::app()->getStores();
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->_restoreSettings($item->children(), $path.'/'.$item->getName());
            } else {
                if ($this->_clear) {
                    Mage::getConfig()->deleteConfig($path.'/'.$item->getName());
                    foreach ($websites as $website) {
                        Mage::getConfig()->deleteConfig($path.'/'.$item->getName(), 'websites', $website->getId());
                    }
                    foreach ($stores as $store) {
                        Mage::getConfig()->deleteConfig($path.'/'.$item->getName(), 'stores', $store->getId());
                    }
                }
                foreach ($this->_stores as $store) {
                    $scope = ($store ? 'stores' : 'default');
                    Mage::getConfig()->saveConfig($path.'/'.$item->getName(), (string)$item, $scope, $store);
                }
            }
        }
    }

}