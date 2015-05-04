<?php
class IWD_All_Block_Adminhtml_Support_Edit_Renderer_Filemultiple extends Varien_Data_Form_Element_File
{
    public function getElementHtml()
    {    
        $html = parent::getElementHtml();
        $newntml = substr_replace($html, 'multiple="true"', -2, 0);
        return $newntml;
    }
}