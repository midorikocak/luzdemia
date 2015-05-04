<?php
class IWD_All_Model_Indexes {
	
	protected function _construct() {
		$this->_init ( 'iwdall/indexes' );
	}
	
	public function GetIndexesColection() {
		$connection = Mage::getSingleton ( 'core/resource' )->getConnection ( 'core_read' );
		$sql = "select * from index_process";
		$rows = $connection->fetchAll ( $sql );
		
		return $rows;
	}
}