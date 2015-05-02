<?php
class Neev_Skuautogenerate_Block_Skuautogenerate extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getSkuautogenerate()     
     { 
        if (!$this->hasData('skuautogenerate')) {
            $this->setData('skuautogenerate', Mage::registry('skuautogenerate'));
        }
        return $this->getData('skuautogenerate');
        
    }
}