<?php
header('Content-Type: text/xml; charset=utf-8', true); //set document header content type to be XML
set_time_limit(1800);
require_once 'app/Mage.php';
Mage::app('default');

try{
   $rss = new SimpleXMLElement('<rss xmlns:g="http://base.google.com/ns/1.0"></rss>');

   $rss->addAttribute('version', '2.0');
   $channel = $rss->addChild('channel'); //add channel node
   $NS = array( 
       'g' => 'http://base.google.com/ns/1.0' 
       // whatever other namespaces you want 
   ); 
   
   foreach ($NS as $prefix => $name) { 
       $rss->registerXPathNamespace($prefix, $name); 
   } 
   
   $NS = (object) $NS; 
   // $atom = $rss->addChild('atom:atom:link'); //add atom node
   // $atom->addAttribute('href', 'http://luzdemia.com'); //add atom node attribute
   // $atom->addAttribute('rel', 'self');
   // $atom->addAttribute('type', 'application/rss+xml');
   
   $rssLink = $channel->addChild('link','http://www.luzdemia.com'); //feed site
   $rssTitle = $channel->addChild('title','Luzdemia Products'); //feed title
   $rssDescription = $channel->addChild('description','This is the Luzdemia\'s visible products data feed. Created by Midori Kocak mtkocak@mtkocak.net'); //feed description
   
   $products = Mage::getModel('catalog/product')->getCollection();
   $products->addAttributeToFilter('status', 1);
   $products->addAttributeToFilter('visibility', 4);
   $products->addAttributeToFilter('sku', array('nlike' => 'SE%'));
   $products->addAttributeToFilter('sku', array('nlike' => 'KD%'));
   $products->addAttributeToFilter('sku', array('nlike' => 'PL%'));
   $products->addAttributeToSelect('*');
   $prodIds=$products->getAllIds();
   $catalogRule = Mage::getModel('catalogrule/rule');

   $product = Mage::getModel('catalog/product');

   $counter_test = 0;

   foreach($prodIds as $productId) {
      if (++$counter_test < 30000){
         $product->load($productId);
         $item = $channel->addChild('item'); //add item node
         $title = $item->addChild('title', $product->getName());
         $description = $item->addChild('description', substr(iconv("UTF-8","UTF-8//IGNORE",htmlspecialchars($product->getDescription())), 0, 900));
         $image = $item->addChild('g:image_link',Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$product->getImage(),$NS->g);
         $price = $item->addChild('g:price',round($product->getPrice(),2),$NS->g);
         $id = $item->addChild('g:id',$product->getSku(),$NS->g);
         
         if(substr($product->getSku(),0,2)=='ER'){
            $category  = $item->addChild('g:google_product_category','Apparel &amp; Accessories &gt; Jewelry &gt; Earrings',$NS->g);
            $type  = $item->addChild('g:product_type','Apparel &amp; Accessories &gt; Jewelry &gt; Earrings',$NS->g);
         }elseif(substr($product->getSku(),0,2)=='KR' || substr($product->getSku(),0,2)=='MM' || substr($product->getSku(),0,2)=='NC' || substr($product->getSku(),0,2)=='RL' || substr($product->getSku(),0,2)=='TG'){
            $category  = $item->addChild('g:google_product_category','Apparel &amp; Accessories &gt; Jewelry &gt; Necklaces',$NS->g);
            $type  = $item->addChild('g:product_type','Apparel &amp; Accessories &gt; Jewelry &gt; Necklaces',$NS->g);
         }elseif(substr($product->getSku(),0,2)=='CB'){
            $category  = $item->addChild('g:google_product_category','Apparel &amp; Accessories &gt; Jewelry &gt; Bracelets',$NS->g);
            $type  = $item->addChild('g:product_type','Apparel &amp; Accessories &gt; Jewelry &gt; Bracelets',$NS->g);
         }elseif(substr($product->getSku(),0,2)=='DN' || substr($product->getSku(),0,2)=='LN'){
            $category  = $item->addChild('g:google_product_category','Apparel &amp; Accessories &gt; Jewelry &gt; Necklaces',$NS->g);
            $type  = $item->addChild('g:product_type','Apparel &amp; Accessories &gt; Jewelry &gt; Pendants',$NS->g);
         }elseif(substr($product->getSku(),0,2)=='LL'){
            $category  = $item->addChild('g:google_product_category','Apparel &amp; Accessories &gt; Jewelry &gt; Necklaces',$NS->g);
            $type  = $item->addChild('g:product_type','Apparel &amp; Accessories &gt; Jewelry &gt; Lockets',$NS->g);
         }elseif(substr($product->getSku(),0,2)=='CH'){
            $category  = $item->addChild('g:google_product_category','Apparel &amp; Accessories &gt; Jewelry &gt; Necklaces',$NS->g);
            $type  = $item->addChild('g:product_type','Apparel &amp; Accessories &gt; Jewelry &gt; Chains',$NS->g);
         }elseif(substr($product->getSku(),0,2)=='LB'){
            $category  = $item->addChild('g:google_product_category','Apparel &amp; Accessories &gt; Jewelry &gt; Bracelets',$NS->g);
            $type  = $item->addChild('g:product_type','Apparel &amp; Accessories &gt; Jewelry &gt; Other',$NS->g);
         }else{
            $category  = $item->addChild('g:google_product_category','Apparel &amp; Accessories &gt; Jewelry &gt; Other',$NS->g);
            $type  = $item->addChild('g:product_type','Apparel &amp; Accessories &gt; Jewelry &gt; Other',$NS->g);
         }
         
         $condition  = $item->addChild('g:condition','new',$NS->g);
         $link  = $item->addChild('g:link','luzdemia.com/'.$product->getUrlPath(),$NS->g);
         if($product->getStockItem()->getQty()==0){
            $availability  = $item->addChild('g:availability','out of stock',$NS->g);
         }else{
            $availability  = $item->addChild('g:availability','in stock',$NS->g);
         }
      }
   }
   echo $rss->asXML();
}
catch(Exception $e){
   die($e->getMessage());
}
