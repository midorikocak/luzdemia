<?php

class Dolphin_Slideshow_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getSlidesPath()
	{
		return 'slideshow' . "/" . 'slides' . "/";
	}
	public function getThumbsPath($slidesPath)
	{
		return str_replace("/slides/","/slides/thumbs/",$slidesPath);
	}

	public function resizeImg($fileName, $width, $height = '')
	{
		//$fileName = "slideshow\slides\\".$fileName;
		
		$folderURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		$imageURL = $folderURL . $fileName;
	 
		$basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "slideshow". DS . "slides" . DS . $fileName;
		
		$newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "slideshow" . DS . "slides" . DS . "thumbs" . DS . $fileName;
		//if width empty then return original size image's URL
		if ($width != '') {
			//if image has already resized then just return URL
			if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
				
				$imageObj = new Varien_Image($basePath);
				$imageObj->constrainOnly(TRUE);
				$imageObj->keepAspectRatio(FALSE);
				$imageObj->keepFrame(FALSE);
				$imageObj->resize($width, $height);
				$imageObj->save($newPath);
	
			}
			$resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "resized" . DS . $fileName;
		 } else {
			$resizedURL = $imageURL;
		 }
		 return $resizedURL;
	}

}