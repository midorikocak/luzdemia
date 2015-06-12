<?php
class Dolphin_Slideshow_Block_Adminhtml_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if($row->getData($this->getColumn()->getIndex())==""){
            return "";
        }
        else{
            $html = '<img ';
            $html .= 'id="' . $this->getColumn()->getId() . '" ';
            $html .= 'width="60" ';
            $html .= 'height="45" ';
            $html .= 'src="' . Mage::getBaseUrl("media") . Mage::helper('slideshow')->getThumbsPath($row->getData($this->getColumn()->getIndex())) . '"';
            $html .= 'class="grid-image ' . $this->getColumn()->getInlineCss() . '"/>';
            
            return $html;
        }
    }
} 