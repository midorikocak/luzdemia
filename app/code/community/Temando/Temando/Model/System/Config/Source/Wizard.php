<?php

class Temando_Temando_Model_System_Config_Source_Wizard extends Temando_Temando_Model_System_Config_Source
{

    const ACCOUNT = 1;
    const CARRIERS = 2;
    const CATALOG_PRODUCT = 3;
    const ORIGIN = 4;
    const RULE = 5;
    const END = 6;

    protected function _setupOptions()
    {
        $this->_options = array(
            self::ACCOUNT  => 'temando/temando/wizard/account.phtml',
            self::CARRIERS => 'temando/temando/wizard/carriers.phtml',
            self::CATALOG_PRODUCT => 'temando/temando/wizard/catalog_product.phtml',
            self::ORIGIN => 'temando/temando/wizard/origin.phtml', 
            self::RULE => 'temando/temando/wizard/rule.phtml',
            self::END => 'temando/temando/wizard/end.phtml'
        );
    }

}
