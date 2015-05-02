<?php


class Magik_Deziresettings_Helper_Data extends Mage_Core_Helper_Abstract
{

	protected function _loadProduct(Mage_Catalog_Model_Product $product)
	{
		$product->load($product->getId());
	}

     	public function getLabel(Mage_Catalog_Model_Product $product)
	{
		$html = '';
		if (!Mage::getStoreConfig("deziresettings/deziresettings_labels/new_label") &&
			!Mage::getStoreConfig("deziresettings/deziresettings_labels/sale_label") ) {
			return $html;
		}

		$this->_loadProduct($product);

		if ( Mage::getStoreConfig("deziresettings/deziresettings_labels/new_label") && $this->_isNew($product) ) {
			$html .= '<div class="new-label new-'.Mage::getStoreConfig('deziresettings/deziresettings_labels/new_label_position').'"></div>';
		}
		if ( Mage::getStoreConfig("deziresettings/deziresettings_labels/sale_label") && $this->_isOnSale($product) ) {
			$html .= '<div class="sale-label sale-'.Mage::getStoreConfig('deziresettings/deziresettings_labels/sale_label_position').'"></div>';
		}

		return $html;
	}  

     	protected function _checkDate($from, $to)
	{
		$date = new Zend_Date();
		$today = strtotime($date->__toString());

		if ($from && $today < $from) {
			return false;
		}
		if ($to && $today > $to) {
			return false;
		}
		if (!$to && !$from) {
			return false;
		}
		return true;
	}  

    	protected function _isNew($product)
	{
		$from = strtotime($product->getData('news_from_date'));
		$to = strtotime($product->getData('news_to_date'));

		return $this->_checkDate($from, $to);
	}  

     	protected function _isOnSale($product)
	{
		$from = strtotime($product->getData('special_from_date'));
		$to = strtotime($product->getData('special_to_date'));

		return $this->_checkDate($from, $to);
	}  


}