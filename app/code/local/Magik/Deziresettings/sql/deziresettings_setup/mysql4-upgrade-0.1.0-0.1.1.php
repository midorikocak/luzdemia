<?php


$installer = $this;
$installer->startSetup();
$installer->endSetup();

try {
//create pages and blocks programmatically

//Custom Tab1
$staticBlock = array(
    'title' => 'Custom Tab1',
    'identifier' => 'dezire_custom_tab1',
    'content' => "<p><strong>Lorem Ipsum</strong><span>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>",
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Custom Tab2
$staticBlock = array(
    'title' => 'Custom Tab2',
    'identifier' => 'dezire_custom_tab2',
    'content' => "<p><strong>Lorem Ipsum</strong><span>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>",
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Empty Category
$staticBlock = array(
    'title' => 'Empty Category',
    'identifier' => 'dezire_empty_category',
    'content' => "<p>There are no products matching the selection.<br /> This is a static CMS block displayed if category is empty. You can put your own content here.</p>",
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Dezire Logo Brand block
 $staticBlock = array(
     'title' => 'Dezire Logo Brand block',
     'identifier' => 'dezire_logo_brand_block',
     'content' => '<div class="brand-logo"><div class="jcarousel-skin-tango">
<div id="mycarousel3" class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
<div class="jcarousel-clip jcarousel-clip-horizontal" style="overflow: hidden; position: relative;">
<ul class="jcarousel-list jcarousel-list-horizontal" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 1940px;">
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo1.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-2 jcarousel-item-2-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo2.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-3 jcarousel-item-3-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo3.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-4 jcarousel-item-4-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo4.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-5 jcarousel-item-5-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo5.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-6 jcarousel-item-6-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo6.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-7 jcarousel-item-7-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo3.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-8 jcarousel-item-8-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo2.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-9 jcarousel-item-9-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo5.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-10 jcarousel-item-10-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo4.png"}}" alt="brand-logo" /></li>
</ul>
</div>
<div class="jcarousel-prev jcarousel-prev-horizontal" style="display: block;">&nbsp;</div>
<div class="jcarousel-next jcarousel-next-horizontal" style="display: block;">&nbsp;</div>
</div>
</div></div>',
     'is_active' => 1,
     'stores' => array(0)
 );
 Mage::getModel('cms/block')->setData($staticBlock)->save();

//Dezire Store Logo
$staticBlock = array(
    'title' => 'Dezire Store Logo',
    'identifier' => 'dezire_logo',
    'content' => '<p><img src="{{skin url="images/logo.png"}}" alt="Dezire Store" /></p>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


// dezire navigation block
$staticBlock = array(
    'title' => 'Custom',
    'identifier' => 'dezire_navigation_block',
    'content' => '<div class="grid12-5">
<h4 class="heading">HTML5 + CSS3</h4>
<div class="ccs3-html5-box"><em class="icon-html5">&nbsp;</em> <em class="icon-css3">&nbsp;</em></div>
<p>Our designed to deliver almost everything you want to do online without requiring additional plugins.CSS3 has been split into "modules".</p>
</div>
<div class="grid12-5">
<h4 class="heading">Responsive Design</h4>
<div class="icon-custom-reponsive">&nbsp;</div>
<p>Responsive design is a Web design to provide an optimal navigation with a minimum of resizing, and scrolling across a wide range of devices.</p>
</div>
<div class="grid12-5">
<h4 class="heading">Google Fonts</h4>
<div class="icon-custom-google-font">&nbsp;</div>
<p>Our font delivery service is built upon a reliable, global network of servers. Our flexible solution provides multiple implementation options.</p>
</div>
<div class="grid12-5">
<h4 class="heading">Smart Product Grid</h4>
<div class="icon-custom-grid">&nbsp;</div>
<p>Smart Product Grid is uses maximum available width of the screen to display content. It can be displayed on any screen or any devices.</p>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Dezire Home Banner Block
$staticBlock = array(
    'title' => 'Dezire Home Offer Banner Block',
    'identifier' => 'dezire_home_offer_banner_block',
    'content' => '<div class="offer-banner"><div class="offer-banner-section">
<div class="col"><img src="{{skin url="images/offer_banner1.png"}}" alt="Offer Banner" /></div>
<div class="col-last">
<div class="add-banner"><img src="{{skin url="images/offer_banner2.png"}}" alt="Offer Banner" /></div>
<div class="add-banner1">
<div class="add-banner2"><img src="{{skin url="images/offer_banner3.png"}}" alt="Offer Banner" /></div>
<div class="add-banner3"><img src="{{skin url="images/offer_banner4.png"}}" alt="Offer Banner" /></div>
</div>
</div>
</div></div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Dezire Home Header Block
$staticBlock = array(
    'title' => 'Dezire Header Block',
    'identifier' => 'dezire_header_block',
    'content' => '<div class="service-section"><div id="store-messages" class="messages-3">
<div class="message"><em class="icon-refresh">&nbsp;</em> <span><strong>Return &amp; Exchange</strong> in 3 working days </span></div>
<div class="message"><em class="icon-truck">&nbsp;</em><span><strong>FREE SHIPPING</strong> order over $99</span></div>
<div class="message"><em class="icon-discount">&nbsp;</em><span><strong>50% OFF</strong> on all products</span></div>
<div class="phone"><em class="icon-phone">&nbsp;</em><span><strong>Need help?</strong> +1 800 123 1234</span></div>
</div></div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();



//Dezire Footer About Us Block
$staticBlock = array(
    'title' => 'Dezire Footer About Us',
    'identifier' => 'dezire_footer_about_us',
    'content' => '<div class="footer-column-1">
<div class="footer-logo"><a title="Logo" href="#"><img src="{{skin url="images/logo.png"}}" alt="" /></a></div>
<address>123 Main Street, Anytown,<br /> CA 12345 USA</address>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Dezire Static Banner Block
$staticBlock = array(
    'title' => 'Dezire Static Banner Block',
    'identifier' => 'dezire_static_banner_block',
    'content' => '<div class="our-features-box"><div class="store-img-box">
<ul>
<li>
<div class="feature-box">
<div class="icon-reponsive">&nbsp;</div>
<div class="content">Responsive Theme<span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </span></div>
</div>
</li>
<li>
<div class="feature-box">
<div class="icon-admin">&nbsp;</div>
<div class="content">Powerful Admin Panel <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </span></div>
</div>
</li>
<li class="last">
<div class="feature-box">
<div class="icon-support">&nbsp;</div>
<div class="content">Premium Support <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span></div>
</div>
</li>
</ul>
</div></div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();



//Dezire Home Shipping Banner Block
$staticBlock = array(
    'title' => 'Dezire Home Shipping Banner Block',
    'identifier' => 'dezire_home_shipping_banner_block',
    'content' => '<div class="shipping-banner"><img src="{{skin url="images/promotion-banner.png"}}" alt="promotion banner" /></div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Dezire Listing Page Block
$staticBlock = array(
    'title' => 'Dezire Listing Page Block',
    'identifier' => 'dezire_listing_page_block',
    'content' => '<div class="block block-banner"><a href="#"><img src="{{skin url="images/block-banner.png"}}" alt="" /></a></div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Dezire RHS Contact Form Block
$staticBlock = array(
    'title' => 'Dezire RHS Contact Form Block',
    'identifier' => 'dezire_rhs_contact_form_block',
    'content' => '<div class="slider-phone active">
<h2>TALK TO US</h2>
<h3>AVAILABLE 24/7</h3>
<p class="textcenter">Want to speak to someone? We \'re here 24/7 to answer any questions. Just call!<br /> <br /> <span class="phone-number"> +1 800 123 1234</span></p>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Dezire Navigation Featured Product Block
$staticBlock = array(
    'title' => 'Dezire Navigation Featured Product Block',
    'identifier' => 'dezire_navigation_featured_product_block',
    'content' => '<p>{{block type="catalog/product_new"  products_count="1" name="home.catalog.product.new" as="newproduct" template="catalog/product/new.phtml" }}</p>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Dezire Home Slider Banner Block
$staticBlock = array(
    'title' => 'Dezire Home Slider Banner Block',
    'identifier' => 'dezire_home_slider_banner_block',
    'content' => '<div class="slider_wrapper">
<div id="sequence-theme">
<div id="sequence">
<ul class="controls">
<li><a class="sequence-prev" style="display: inline;">Prev</a></li>
<li><a class="sequence-next" style="display: inline;">Next</a></li>
</ul>
<ul class="sequence-canvas">
<li class="animate-out" style="display: list-item; opacity: 1; z-index: 1;"><img class="model" src="{{skin url="images/banner-img.jpg"}}" alt="Image" /></li>
<li class="animate-out" style="display: list-item; opacity: 1; z-index: 1;"><img class="model-slider2" src="{{skin url="images/banner-img1.jpg"}}" alt="Image" /></li>
<li class="animate-in" style="display: list-item; opacity: 1; z-index: 3;"><img class="model-slider3" src="{{skin url="images/banner-img2.jpg"}}" alt="Image" /></li>
</ul>
</div>
</div>
<script type="text/javascript">
    $jq(document).ready(function(){       
        var options = {
            autoPlay: true,
            autoPlayDelay: 6000,
            pauseOnHover: false, 
            hidePreloaderDelay: 500,
            nextButton: true,
            prevButton: true,
            pauseButton: true,
            preloader: true,
            pagination:true,
            hidePreloaderUsingCSS: false,                   
            animateStartingFrameIn: true,    
            navigationSkipThreshold: 750,
            preventDelayWhenReversingAnimations: true,
            customKeyEvents: {
                80: "pause"
            }
        };
        var sequence = $jq("#sequence").sequence(options).data("sequence");           
    });
</script>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();




//Dezire Footer Information Links Block
$staticBlock = array(
    'title' => 'Dezire Footer Information Links Block',
    'identifier' => 'dezire_footer_information_links_block',
    'content' => '<div class="footer-column">
<h4>Shopping Guide</h4>
<ul class="links">
<li class="first"><a title="How to buy" href="#">How to buy</a></li>
<li><a title="FAQs" href="#">FAQs</a></li>
<li><a title="Payment" href="#">Payment</a></li>
<li><a title="Shipment" href="#">Shipment</a></li>
<li><a title="Where is my order?" href="#">Where is my order?</a></li>
<li class="last"><a title="Return policy" href="#">Return policy</a></li>
</ul>
</div>
<div class="footer-column">
<h4>Style Advisor</h4>
<ul class="links">
<li class="first"><a title="Your Account" href="{{store_url=customer/account/}}">Your Account</a></li>
<li><a title="Information" href="#">Information</a></li>
<li><a title="Addresses" href="#">Addresses</a></li>
<li><a title="Addresses" href="#">Discount</a></li>
<li><a title="Orders History" href="#">Orders History</a></li>
<li class="last"><a title=" Additional Information" href="#"> Additional Information</a></li>
</ul>
</div>
<div class="footer-column">
<h4>Information</h4>
<ul class="links">
<li class="first"><a title="Site Map" href="{{store_url=catalog/seo_sitemap/category/}}">Site Map</a></li>
<li><a title="Search Terms" href="{{store_url=catalogsearch/term/popular/}}">Search Terms</a></li>
<li><a title="Advanced Search" href="{{store_url=catalogsearch/advanced/}}">Advanced Search</a></li>
<li><a title="History" href="# ">History</a></li>
<li><a title="Suppliers" href="#">Suppliers</a></li>
<li class=" last"><a class="link-rss" title="Our stores" href="#">Our stores</a></li>
</ul>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();



}
catch (Exception $e) {
    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('An error occurred while installing Dezire theme pages and cms blocks.'));
}