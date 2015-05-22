 <?php

class Magik_Socialbar_Model_Socialsites extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('socialbar/socialsites');
    }
	
	
	public function getButtons()
    {
		$tableName = (string)Mage::getConfig()->getTablePrefix().'magik_socialsites';
		$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('core_read');
		$res = $read->fetchAll("select * from ".$tableName." ");
		return $res;
	}
}