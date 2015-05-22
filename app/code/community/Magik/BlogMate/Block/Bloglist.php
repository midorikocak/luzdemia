<?php
   
class Magik_Blogmate_Block_Bloglist extends Mage_Core_Block_Template
{
    public function __construct()
    {   $storeId=Mage::app()->getStore()->getStoreId();
        parent::__construct();
    $collection = Mage::getModel('blogmate/blogmate')->getCollection()
                    ->addAttributeToSelect('*')
                    ->setStoreId($storeId);
                
        $this->setCollection($collection);
    }
 
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
 
   

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(20=>20,'all'=>'all'));
       $pager->setShowPerPage(true);
           $pager->setShowAmounts(false);

        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }
 
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    } 

    
}
