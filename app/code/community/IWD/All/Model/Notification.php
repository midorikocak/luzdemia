<?php
class IWD_All_Model_Notification extends Mage_Core_Model_Abstract
{
    const SEVERITY_CRITICAL = 1;
    const SEVERITY_MAJOR = 2;
    const SEVERITY_MINOR = 3;
    const SEVERITY_NOTICE = 4;

    protected function _construct()
    {
        $this->_init('iwdall/notification');
    }

    public function parse(array $data)
    {
        return $this->getResource()->parse($this, $data);
    }
}
