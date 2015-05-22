<?php
class Magik_Scrollingcart_Model_Mysql4_Scrollingcart extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("scrollingcart/scrollingcart", "id");
    }
}