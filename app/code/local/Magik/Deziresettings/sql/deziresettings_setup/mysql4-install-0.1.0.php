<?php


$installer = $this;
$installer->startSetup();

$installer->endSetup();

try {
//create pages and blocks programmatically
//home page
$cmsPage = array(
    'title' => 'Dezire Home Page',
    'identifier' => 'dezire_home',
    'content' => "<div>{{block type=\"catalog/product_list\" num_products=\"8\" name=\"bestsellerproduct\" as=\"bestsellerproduct\" template=\"catalog/product/bestseller.phtml\" }}</div>"
,
    'is_active' => 1,
    'sort_order' => 0,
    'stores' => array(0),
    'root_template' => 'one_column'
);
Mage::getModel('cms/page')->setData($cmsPage)->save();
//404 page
$cmsPage = array(
    'title' => 'Dezire 404 No Route',
    'identifier' => 'dezire_no_route',
    'content' => '<div class="login_page">
<div class="page-title">
<h3>Whoops, our bad...</h3>
</div>
<dl><dt>The page you requested was not found, and we have a fine guess why.</dt><dd>
<ul class="disc">
<li>If you typed the URL directly, please make sure the spelling is correct.</li>
<li>If you clicked on a link to get here, the link is outdated.</li>
</ul>
</dd></dl>
<p>&nbsp;</p>
<dl><dt>What can you do?</dt><dd>Have no fear, help is near! There are many ways you can get back on track with Magento Demo Store.</dd><dd>
<ul class="buttons">
<li><button class="button" onclick="history.go(-1);"><span><span>Go back</span></span></button></li>
<li><button class="button" onclick="location.href=\'{{store url=\'\'}}\'"><span><span>Store Home</span></span></button></li>
<li><button class="button" onclick="location.href=\'{{store url=\'customer/account\'}}\'"><span><span>My Account</span></span></button></li>
</ul>
</dd></dl></div>
',
    'is_active' => 1,
    'sort_order' => 0,
    'stores' => array(0),
    'root_template' => 'one_column'
);
Mage::getModel('cms/page')->setData($cmsPage)->save();


//footer links
$staticBlock = array(
    'title' => 'Dezire Footer links',
    'identifier' => 'dezire_footer_links',
    'content' => '<div class="footer-bottom">
<div class="inner">
<div class="coppyright">Copyright &copy; 2014. All Rights Reserved. Designed by <a href="http://www.magikcommerce.com">magikcommerce.com</a></div>
<div class="bottom_links">
<ul class="links">
<li><a title="Magento Themes" href="http://www.magikcommerce.com/magento-themes-templates">Magento Themes</a></li>
<li><a title="Responsive Themes" href="http://www.magikcommerce.com/magento-themes-templates/responsive-themes">Responsive Themes</a></li>
<li class="last"><a title="Magento Extensions" href="http://www.magikcommerce.com/magento-extensions">Magento Extensions</a></li>
</ul>
</div>
</div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();
}
catch (Exception $e) {
    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('An error occurred while installing dezire theme pages and cms blocks.'));
}