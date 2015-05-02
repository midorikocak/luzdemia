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
class Toogas_Featuredpopup_Block_Featuredpopup extends Mage_Core_Block_Template {

	public $featuredpopupvar;
	private $image;
	private $url = '';
	private $width_image = 0;
	private $height_image = 0;
	private $delay_start = 0;
	private $delay_close;
	private $opacity = 0.4;
	public $okShowPopup = 0;

	public function __construct() {

		if (!$this->helper('featuredpopup')->getSystemStatus()) {
			return;
		};

		$magento_now_date = $this->magentoNowDate();
		$this->featuredpopupvar = Mage :: getModel('featuredpopup/featuredpopup')->getCollection()->addStoreFilter(Mage :: app()->getStore()->getId())->addFilter('is_active', 1)->addFieldToFilter('from_date', array (
			'datetime' => true,
			'to' => $magento_now_date
		))->addFieldToFilter('to_date', array (
			'datetime' => true,
			'from' => $magento_now_date
		))->setOrder('priority');

		foreach ($this->featuredpopupvar as $vars) {

			$this->image = $vars->getImageLink();
			$this->url = $vars->getUrlLink();
			$this->width_image = $vars->getWidthImage();
			$this->height_image = $vars->getHeightImage();
			$this->delay_start = $vars->getDelayStart();
			$this->delay_close = $vars->getDelayClose();
			$this->opacity = $vars->getOpacity();

			$this->okShowPopup = 1;
		}

	}

	public function _prepareLayout() {
		return parent :: _prepareLayout();
	}

	public function obtainImage() {
		return Mage :: getBaseUrl(Mage_Core_Model_Store :: URL_TYPE_MEDIA) . $this->image;
	}

	public function obtainUrl() {
		return $this->url;
	}

	public function obtainWidthImage() {
		return (is_numeric($this->width_image) && $this->width_image > 0) ? $this->width_image : 0;
	}

	public function obtainHeightImage() {
		return (is_numeric($this->height_image) && $this->height_image > 0) ? $this->height_image : 0;
	}

	public function obtainDelayStart() {
		return (is_numeric($this->delay_start) && $this->delay_start > 0) ? $this->delay_start : 0;
	}

	public function obtainDelayClose() {
		return (is_numeric($this->delay_close) && $this->delay_close > 0) ? $this->delay_close : 0;
	}

	public function obtainOpacity() {
		return (is_numeric($this->opacity) && $this->opacity > 0 && $this->opacity <= 1) ? $this->opacity : 0.4;
	}

	private function magentoNowDate() {
		$now = Mage :: getModel('core/date')->timestamp(time());
		return date('Y-m-d H:i:s', $now);
	}

}