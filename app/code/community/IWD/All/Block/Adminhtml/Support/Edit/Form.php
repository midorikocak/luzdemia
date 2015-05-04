<?php
class IWD_All_Block_Adminhtml_Support_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $userId = Mage::getSingleton('admin/session')->getUser()->getId();
        $user = Mage::getModel('admin/user')->load($userId);
            
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/send'),
                'method' => 'post',
                'enctype' => "multipart/form-data",
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        $helper = Mage::helper('iwdall');

        $fieldset = $form->addFieldset('display', array(
            'legend' => $helper->__('Support Tickets'),
            'class'  => 'fieldset-wide'
        ));

        $fieldset->addField('type', 'select', array(
            'name'     => 'subject',
            'label'    => $helper->__('Type of issue'),
            'values'   => Mage::getModel('iwdall/issuetype')->toOptionArray(),
            'required' => true,
            'value' => false
        ));
        $fieldset->addField('description', 'textarea', array(
            'name'     => 'description',
            'label'    => $helper->__('Describe your issue'),
            'required' => true,
        ));

        $fieldset->addType('filemultiple', Mage::getConfig()
            ->getBlockClassName('iwdall/adminhtml_support_edit_renderer_filemultiple'));
            
        $fieldset->addField('attachments', 'filemultiple', array(
            'name'     => 'attachments[]',
            'multiple' => true,
            'label'    => Mage::helper('adminhtml')->__('Attachment files'),
        ));    
        
            $fieldset->addField('email', 'text', array(
            'name'     => 'email',
            'label'    => Mage::helper('adminhtml')->__('Your e-mail'),
            'value'    => $user['email'], 
            'required' => true,
        ));     
        $fieldset->addField('name', 'text', array(
            'name'     => 'name',
            'label'    => Mage::helper('adminhtml')->__('Your name'),
            'value'    => "{$user['firstname']} {$user['lastname']}", 
            'required' => true,
        ));
        
        $fieldset->addField('informaion', 'hidden', array(
            'name'  => 'informaion',
            'value' => $helper->CollectorInformation(),
        ));
        
        $fieldset->addField('send', 'submit', array(
            'name'  => 'submit',
            'class' => 'save',
            'value' => $helper->__('Send request'),
        ));

        echo '<div style="display:none">' . Mage::helper("adminhtml")->getUrl("*/adminhtml_conflicts") . '</div>';

        if (Mage::registry('iwdall')) {
            $form->setValues(Mage::registry('iwdall')->getData());
        }

        return parent::_prepareForm();
    }
}
