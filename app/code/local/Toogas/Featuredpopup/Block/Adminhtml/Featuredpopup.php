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
class Toogas_Featuredpopup_Block_Adminhtml_Featuredpopup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {   	
  	
    $this->_controller = 'adminhtml_featuredpopup';
    $this->_blockGroup = 'featuredpopup';
    $this->_headerText = Mage::helper('featuredpopup')
    ->__('Featured Popup (Free) <a target=_blank href="http://www.toogas.com/featured-pop-up.html?___store=en&___from_store=pt">Click here for the Pro Version</a>');
    $this->_addButtonLabel = Mage::helper('featuredpopup')->__('Add Featured Popup');
    parent::__construct();
  }
}