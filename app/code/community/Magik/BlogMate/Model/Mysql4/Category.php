<?php
class Magik_BlogMate_Model_Mysql4_Category extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("blogmate/category", "id");
    }
}