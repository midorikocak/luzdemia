<?php

class Lema21_CustomExport_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Get array of itens in template line
     * 
     * @return array
     */ 
    public static function loadTemplate()
    {
        $io = new Varien_Io_File();
            
        $pathToTile = Mage::getBaseDir('app') . DS . 
            'code/local/Lema21/CustomExport/Template/template.csv';

        // load csv
        $contentTemplate = file_get_contents($pathToTile);

        $templateLine = explode("|", $contentTemplate);

        return $templateLine;
    }
}