<?php
class IWD_All_Block_Adminhtml_Support_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct() {
		parent::__construct ();
		
		$this->_controller = 'adminhtml_support';
		$this->_blockGroup = 'iwdall';
		
		$this->_removeButton ( 'reset' );
		$this->_removeButton ( 'save' );
		$this->_removeButton ( 'back' );
		$this->_headerText = Mage::helper ( 'iwdall' )->__ ( 'IWD Support Ticket System' );
	}
}
