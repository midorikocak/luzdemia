<?php
/**
 * Toogas Lda.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA (End-User License Agreement)
 * that is bundled with this package in the file licence_toogas_community.txt.
 * It is also available at this URL:
 * http://www.toogas.com/licences/toogas_community.txt
 *
 * @category   Toogas
 * @package    Toogas_Toogaslda
 * @copyright  Copyright (c) 2010 Toogas Lda. (http://www.toogas.com)
 * @license    http://www.toogas.com/licences/toogas_community.txt
 */	  	
class Toogas_Toogaslda_Block_Adminhtml_System_Config_Fieldset_Toogas
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'toogas_toogaslda/system/config/fieldset/toogas.phtml';
    
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }
}
