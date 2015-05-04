<?php

class IWD_All_Model_Mysql4_Notification extends Mage_Core_Model_Mysql4_Abstract{
	
    protected function _construct()
    {
        $this->_init('iwdall/notification', 'entity_id');
    }

    
    public function parse(IWD_All_Model_Notification $object, array $data)
    {
    	$write = $this->_getWriteAdapter();
    	
    	foreach ($data as $item) {
    		
    		$select = $write->select()->from($this->getMainTable())->where('out_id=?', $item['out_id']);
    		
    		$row = $write->fetchRow($select);
    		if (!$row) {
    			$write->insert($this->getMainTable(), $item);    
    		}		
    	}
    }
}
