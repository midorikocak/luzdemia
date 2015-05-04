<?php
class IWD_All_Helper_Data extends Mage_Core_Helper_Abstract
{
    const TO_EMAIL = 'help@iwdextensions.com';
    const TO_NAME = 'Support';

    private $information = '<table rules="all" frame="border">';

    /**
     * Collecting information about the system
     */
    public function CollectorInformation()
    {
        $this->MagentoInfo();
        $this->CacheInfo();
        $this->IndexInfo();
        $this->ExtraneousModulesInfo();
        $this->MySqlInfo();
        $this->ConfigInfo();
        $this->ExtensionsInfo();

        return $this->getTableInformation();
    }

    /**
     * Get information about IWD Extensions installed  on this page
     */
    public function InformationAboutIWDExtensions()
    {
        $extensions_info = "<ul>";

        $modules = ( array )Mage::getConfig()->getNode('modules')->children();
        foreach ($modules as $key => $value) {
            if ((strpos($key, 'IWD_', 0) === 0)) {
                $extensions_info .= "<li>{$key} (v {$value->version}) - {$value->active} - ({$value->codePool})</li>";
            }
        }

        return $extensions_info . "</ul>";
    }

    private function getTableInformation()
    {
        return ($this->information .= '</table>');
    }

    private function CacheInfo()
    {
        $this->AddRowTable("Magento: Cache Storage Management");

        foreach (Mage::app()->getCacheInstance()->getTypes() as $type) {
            $this->AddRowTable($type ['cache_type'], $type ['status']);
        }
    }

    private function IndexInfo()
    {
        $this->AddRowTable("Magento: Index Management");

        $indexes = Mage::getModel('index/process')->getCollection();

        foreach ($indexes as $item) {
            $info = "MODE: {$item->getMode()},<br />
			STATUS: {$item->getStatus()},<br />
			UPDATE AT: {$item->getEndedAt()}";
            $this->AddRowTable($item ['indexer_code'], $info);
        }
    }

    private function MagentoInfo()
    {
        $this->AddRowTable("Magento information");

        $this->AddRowTable('Magento version', Mage::getVersion());

        $compilerConfig = '../includes/config.php';
        if (file_exists($compilerConfig)) {
            include $compilerConfig;
        }

        $this->AddRowTable('Compilation', defined('COMPILER_INCLUDE_PATH') ? 'Enabled' : 'Disabled');

        $this->AddRowTable('Domain', $_SERVER ["HTTP_HOST"]);
    }

    private function ExtraneousModulesInfo()
    {
        $this->AddRowTable("Advanced modules");

        $modules = ( array )Mage::getConfig()->getNode('modules')->children();
        foreach ($modules as $key => $value) {
            if ((strpos($key, 'Mage_', 0) !== 0)) {
                $this->AddRowTable("{$key} (v {$value->version})", "{$value->active} ({$value->codePool})");
            }
        }
    }

    private function MySqlInfo()
    {
        $this->AddRowTable("MySql information");
        preg_match('/[0-9]\.[0-9]+\.[0-9]+/', shell_exec('mysql -V'), $version);
        if (empty($version [0]) || empty($version [0])) {
            $this->AddRowTable('MySql version', 'N/A');
            return;
        }

        $this->AddRowTable('MySql version', $version [0]);
    }

    private function ConfigInfo()
    {
        $this->AddRowTable("Configuration");
        $this->AddRowTable('PHP version', phpversion());
        $ini = array(
            'safe_mode',
            'memory_limit',
            'realpath_cache_ttl',
            'allow_url_fopen'
        );
        foreach ($ini as $i) {
            $val = ini_get($i);
            $val = empty ($val) ? 'off' : $val;
            $this->AddRowTable($i, $val);
        }
    }

    private function ExtensionsInfo()
    {
        $this->AddRowTable("Extensions");
        $extensions = array(
            'curl',
            'dom',
            'gd',
            'hash',
            'iconv',
            'mcrypt',
            'pcre',
            'pdo',
            'pdo_mysql',
            'simplexml'
        );
        foreach ($extensions as $extension)
            $this->AddRowTable($extension, extension_loaded($extension));
    }

    private function AddRowTable($column1, $column2 = "")
    {
        if ($column2 === "") {
            $this->information .= "<tr><td colspan='2' align='center'><b>{$column1}</b></td></tr>";
        } else {
            $this->information .= "<tr><td>{$column1}</td><td>{$column2}</td></tr>";
        }
    }

    /**
     * Send email
     */
    public function sendEmail($body, $subject, $fromEmail, $fromName, $attachment)
    {
        $mail = new Zend_Mail ();

        $mail->setBodyHtml($body, "UTF-8");
        $mail->setFrom($fromEmail, $fromName);
        $mail->addTo(self::TO_EMAIL, self::TO_NAME);
        $mail->setSubject($subject);

        for ($i = 0; $i < count($attachment ['name']); $i++) {
            $tmpFilePath = $attachment ['tmp_name'] [$i];
            if ($tmpFilePath != "") {
                $newFilePath = "./media/downloadable/" . $attachment ['name'] [$i];
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $fname = $attachment ['name'] [$i];
                    $at = new Zend_Mime_Part (file_get_contents($newFilePath));
                    $at->disposition = Zend_Mime::DISPOSITION_INLINE;
                    $at->encoding = Zend_Mime::ENCODING_BASE64;
                    $at->filename = $fname;
                    $mail->addAttachment($at);
                }
            }
        }

        try {
            $mail->send();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('iwdall')->__('The letter has been successfully sent.'));
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError(Mage::helper('iwdall')->__($ex->getMessage()));
        }
    }
}
