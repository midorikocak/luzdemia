<?php

class Temando_Temando_Model_Status extends Varien_Object
{
    
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    /**
     * Gets the available statuses.
     *
     * The results are in the format array(code => description).
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED => Mage::helper('temando')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('temando')->__('Disabled')
        );
    }
    
}
