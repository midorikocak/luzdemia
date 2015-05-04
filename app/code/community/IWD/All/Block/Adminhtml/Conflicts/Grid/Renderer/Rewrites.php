<?php
class IWD_All_Block_Adminhtml_Conflicts_Grid_Renderer_Rewrites extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {        
        $rewrites = $row->getRewrites();
        $result = "<ul>";

        foreach($rewrites as $rewrite) {
            $result .= '<li>&bull;&nbsp;' . $rewrite . '</li>';
        }

        return $result . "</ul>";
    }
}