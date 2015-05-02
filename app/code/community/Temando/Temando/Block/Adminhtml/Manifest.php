<?php
class Temando_Temando_Block_Adminhtml_Manifest extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'temando';
        $this->_controller = 'adminhtml_manifest';
        $this->_headerText = Mage::helper('temando')->__('Manage Manifests');
        parent::__construct();
        $this->setTemplate('temando/temando/manifest.phtml');
        $this->removeButton('add');

        $add_button_method = 'addButton';
        if (!method_exists($this, $add_button_method)) {
            $add_button_method = '_addButton';
        }

        $this->$add_button_method('add_form_submit', array(
            'label'     => Mage::helper('temando')->__('Add Manifest'),
            'onclick'   => 'addFormSubmit()'
        ));
        $this->$add_button_method('confirm', array(
            'label'     => Mage::helper('temando')->__('Confirm Manifests'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/*/confirm') .'\')',
        ));
    }

}
