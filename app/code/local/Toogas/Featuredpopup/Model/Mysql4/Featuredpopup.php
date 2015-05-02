<?php
/**
 * Toogas Lda.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA (End-User License Agreement)
 * that is bundled with this package in the file toogas_license-free.txt.
 * It is also available at this URL:
 * http://www.toogas.com/licences/toogas_license-free.txt
 *
 * @category   Toogas
 * @package    Toogas_Featuredpopup
 * @copyright  Copyright (c) 2011 Toogas Lda. (http://www.toogas.com)
 * @license    http://www.toogas.com/licences/toogas_license-free.txt
 */
class Toogas_Featuredpopup_Model_Mysql4_Featuredpopup extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
		
        
        $this->_init('featuredpopup/featuredpopup', 'featuredpopup_id');
    }
    
    
     /**
     * Process page data before saving. As validacoes podem ser feitas aqui
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
    	
    	//compare from to date
    	if ( strtotime($object->getData('from_date')) > strtotime($object->getData('to_date'))) {
    		Mage::throwException(Mage::helper('featuredpopup')->__('Range of dates wrong')); 
    	}     	
     	
        return $this;
    }
        
    
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('featured_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('toogas_featuredpopup_store'), $condition);

        foreach ((array)$object->getData('stores') as $store) {
            $storeArray = array();
            $storeArray['featured_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('toogas_featuredpopup_store'), $storeArray);
        }

        return parent::_afterSave($object);
    }    
    
    

    
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {   				

        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('toogas_featuredpopup_store'))
            ->where('featured_id = ?', $object->getId());
            
		if (!Mage::app()->isSingleStoreMode()) {
        	
        	if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            	$storesArray = array();
            	foreach ($data as $row) {
                	$storesArray[] = $row['store_id'];
            	}
                       
            	$object->setData('store_id', $storesArray);
        	}
        
        }


		else { $object->setData('store_id', Mage::app()->getStore(true)->getId()); }

		
        return parent::_afterLoad($object);
    }
    
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $select->join(
                        array('cps' => $this->getTable('toogas_featuredpopup_store')),
                        $this->getMainTable().'.featured_id = `cps`.featuredpopup_id'
                    )
                    ->where('is_active=1 AND `cps`.store_id in (' . Mage_Core_Model_App::ADMIN_STORE_ID . ', ?) ', $object->getStoreId())
                    ->order('store_id DESC')
                    ->limit(1);
        }
        return $select;
    }   
    
}