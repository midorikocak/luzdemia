<?php
class IWD_All_Block_Adminhtml_Conflicts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'iwdall';
        $this->_controller = 'adminhtml_conflicts';
        $this->_headerText = Mage::helper('iwdall')->__('Extensions Conflict');
        parent::__construct();
        $this->removeButton('add');
    }
}