<?php

class Temando_Temando_Block_Adminhtml_System_Config_Form_Fieldset_General extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    
    /**
     * @see Mage_Adminhtml_Block_System_Config_Form_Fieldset::_getHeaderHtml()
     *
     * Passes the output text through a basic replacement function to find
     * instances of "{{store url="..."}}" and replace these with the correct
     * Magento URLs.
     */
    protected function _getHeaderHtml($element)
    {
        $html = parent::_getHeaderHtml($element);
        $pattern = '#{{store url="(.*?)"}}#';
        
        return preg_replace_callback($pattern, create_function('$elements', '
        	// $elements[1] is the 1st parenthesized expression
            return Mage::getModel("adminhtml/url")->getUrl($elements[1]);
        '), $html);
    }
    
}
