 <?php

    require_once "app/Mage.php";

    Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));


    $installer = new Mage_Sales_Model_Mysql4_Setup;

    // change details below:
    $attribute  = array(
        'group'                => 'General',
        'type'              => 'int',//can be int, varchar, decimal, text, datetime
        'backend'           => '',
        'frontend_input'    => '',
        'frontend'          => '',
        'label'             => 'Wide Images',
        'input'             => 'select', //text, textarea, select, file, image, multilselect
        'default' => array(0),
        'class'             => '',
        'source'            => 'eav/entity_attribute_source_boolean',//this is necessary for select and multilelect, for the rest leave it blank
        'global'             => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,//scope can be SCOPE_STORE or SCOPE_GLOBAL or SCOPE_WEBSITE
        'visible'           => true,
        'frontend_class'     => '',
        'required'          => false,//or true
        'user_defined'      => true,
        'default'           => '',
        'position'            => 100,//any number will do
    );

    $installer->addAttribute('catalog_category', 'wide_images', $attribute);

    $installer->endSetup();