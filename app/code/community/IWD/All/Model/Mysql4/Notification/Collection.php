<?php
class IWD_All_Model_Mysql4_Notification_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract{
	
    public function _construct(){
        $this->_init('iwdall/notification');
    }
    
    public function addRemoveFilter(){
    	$this->getSelect()->where('is_remove=?','0');
    	return $this;
    }
   
}