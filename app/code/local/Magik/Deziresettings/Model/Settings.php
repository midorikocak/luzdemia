<?php


class Magik_Deziresettings_Model_Settings extends Mage_Core_Model_Abstract
{
    /**
     * theme settings
     *
     * @var Varien_Simplexml_Config
     */
    protected $settings;

    /**
     * load theme xml
     */
    public function __construct()
    {
        parent::__construct();

	    $filePath = Mage::getBaseDir().'/app/code/local/Magik/Deziresettings/etc/dezire.xml';
        $this->settings = new Varien_Simplexml_Config($filePath);
        if ( !$this->settings || !is_readable($filePath)) {
            throw new Exception('Can not read theme config file '.$filePath);
        }
    }

    /**
     * create/update cms pages & blocks
     */
    public function setupCms()
    {
       foreach ( $this->settings->getNode('cms/pages')->children() as $item ) {
            $this->_processCms($item, 'cms/page');
        }

	    foreach ( $this->settings->getNode('cms/blocks')->children() as $item ) {
            $this->_processCms($item, 'cms/block');
        }

    }


    /**
     * create/update cms page/static block
     *
     * @param $page SimpleXMLElement
     */
    protected function _processCms($item, $model)
    {
        $cmsPage = array();
        foreach ( $item as $p ) {
            $cmsPage[$p->getName()] = (string)$p;
	        if ( $p->getName() == 'stores' ) {
		        $cmsPage[$p->getName()] = array();
		        foreach ( $p as $store ) {
			        $cmsPage[$p->getName()][] = (string)$store;
		        }
	        }
        }

	    $orig_page = Mage::getModel($model)->getCollection()
            ->addFieldToFilter('identifier', array( 'eq' => $cmsPage['identifier'] ))
            ->load();
        if (count($orig_page)) {
            foreach ($orig_page as $_page) {
                $_page->delete();
            }
        }

	    Mage::getModel($model)->setData($cmsPage)->save();

    }

}
