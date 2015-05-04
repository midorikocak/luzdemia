<?php
class IWD_All_Model_Issuetype
{
    public function toOptionArray()
    {
        $types = array(
            array(
                'value' => false,
                'label' => ""
            ),
            array(
                'value' => "Other issue",
                'label' => "Other issue"
            ),
            array(
                'value' => "New feature request",
                'label' => "New feature request"
            )
        );

        $modules = ( array )Mage::getConfig()->getNode('modules')->children();
        $iwd_modules = array();

        foreach ($modules as $key => $value)
            if (strpos($key, 'IWD_', 0) === 0 and $key != 'IWD_All')
                $iwd_modules [] = array(
                    'value' => $key,
                    'label' => $key
                );

        $types [] = array(
            'value' => $iwd_modules,
            'label' => 'IWD Modules'
        );

        return $types;
    }
}