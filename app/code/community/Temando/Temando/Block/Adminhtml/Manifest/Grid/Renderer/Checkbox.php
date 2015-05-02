<?php

class Temando_Temando_Block_Adminhtml_Manifest_Grid_Renderer_Checkbox extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Massaction
{

    protected function _getCheckboxHtml($value, $checked)
    {
        $disabled = '';
        $checked = ' checked="checked"';
        if ($this->getDisabledRow()) {
            $disabled = ' disabled="disabled"';
            $checked = '';
        }

        return '<input type="checkbox" name="'.$this->getColumn()->getName().'" value="' . $value . '" class="massaction-checkbox"'.$checked.$disabled.' />';
    }
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        if ($row->getType() == 'Confirmed') {
            $this->setDisabledRow(true);
        }

        return parent::render($row);
    }

}
 
