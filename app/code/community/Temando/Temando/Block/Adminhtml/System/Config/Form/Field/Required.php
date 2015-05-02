<?php

class Temando_Temando_Block_Adminhtml_System_Config_Form_Field_Required extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    
    protected $_element;
    
    /**
     * (non-PHPdoc)
     * @see Mage_Adminhtml_Block_System_Config_Form_Field::render()
     *
     * Adds the "required" star to the form field.
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_element = $element;
        $html = parent::render($this->_element);
        
        $html = $this->_addStar($html);
        $html = $this->_addRequiredClass($html);
        
        return $html;
    }
    
    protected function _addStar($html)
    {
        $search = '<label for="' . $this->_element->getHtmlId() . '">' . $this->_element->getLabel() . '</label>';
        $replacement = '<label for="' . $this->_element->getHtmlId() . '">' . $this->_element->getLabel() . ' <span class="required">*</span></label>';
        
        return str_replace($search, $replacement, $html);
    }
    
    protected function _addRequiredClass($html)
    {
        $search = array(
            '#(<input.*class=")([^<>"]*)(".*>)#',
            '#(<select.*class=")([^<>"]*)(".*>)#',
        );
        $replacement = array(
            '$1required-entry $2$3',
            '$1validate-select $2$3',
        );
        
        return preg_replace($search, $replacement, $html);
    }
    
}
