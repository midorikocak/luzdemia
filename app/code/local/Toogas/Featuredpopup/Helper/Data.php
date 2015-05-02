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
class Toogas_Featuredpopup_Helper_Data extends Mage_Core_Helper_Abstract
{

	public function sacaStatus() {
		return array('0' => Mage::helper('featuredpopup')->__('Disabled'), 
		'1' => Mage::helper('featuredpopup')->__('Enabled'));
	}
	
	public function getSystemStatus() {
		return (bool) Mage::getStoreConfigFlag('toogas_featuredpopup/configfeaturedpopup/featured_popup_enabled');
	}


}