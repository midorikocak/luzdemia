<?php
class Midori_Conversion_Model_Observer
{
   
   public $brand = "luzio";
   public $affiliation = "Luzdemia";
   
   private function createProductArray($product, $quantity = null, $price = null, $position = null, $list = null){
      $categoriesList = "";
      $cats = $product->getCategoryIds();
      $sku = Mage::getModel('catalog/product')->load($product->getId())->getSku();
      
      $counter = 0;
      
      foreach ($cats as $category_id) {
         
          $_cat = Mage::getModel('catalog/category')->load($category_id);
          
          if($counter==0){
             $categoriesList .= $_cat->getName();
          }
          else{
             $categoriesList .= ' / '.$_cat->getName();
          }
          $counter++;
      }
       
      
      $productArray = [
         'id'=>$sku,
         'name'=>$product->getName(),
         'category'=>$categoriesList,
         'brand'=> $this->brand
      ];
      
      if($quantity!=null){
         $productArray['quantity'] = $quantity;
      }
      if($position!=null){
         $productArray['position'] = $position;
      }
      if($price!=null){
         $productArray['price'] = $price;
      }
      if($list!=null){
         $productArray['list'] = $list;
      }
      
      return $productArray;
   }
   
   
   public function userRegister(Varien_Event_Observer $observer)
   {
      $event = $observer->getEvent();
      $customer = $event->getCustomer();
      $email = $customer->getEmail();
      //Mage::getModel('customer/session')->setUserRegisterId($customer->getId()); 
      Mage::getModel('customer/session')->setUserRegister($email); 
   }
   
   public function userLogin(Varien_Event_Observer $observer)
   {
      $event = $observer->getEvent();
      $customer = $event->getCustomer();
      $email = $customer->getEmail();
      //Mage::getModel('customer/session')->setUserLoginId($customer->getId()); 
      Mage::getModel('customer/session')->setUserLogin($email); 
   }
   
   public function purchaseProduct(Varien_Event_Observer $observer)
   {
      $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
      $order = Mage::getModel('sales/order')->load($orderId);
      
      $items = $order->getAllVisibleItems();
         foreach($items as $product){
            $productArray = $this->createProductArray($product,$product->getData('qty_ordered'),$product->getFinalPrice());
         }
      Mage::getModel('customer/session')->setPurchasedProducts(json_encode($productArray));
      $purchase = [];
      $purchase['id'] = $orderId;
      $purchase['affiliation'] = $this->affiliation;
      $purchase['revenue'] = $order->getGrandTotal() - $order->getShippingAmount();
      $purchase['shipping'] = $order->getShippingAmount();
      $purchase['tax'] = $order->getFullTaxInfo();
      $purchase['coupon'] = $order->getCouponCode();
      
      Mage::getModel('customer/session')->setPurchaseProduct(json_encode($purchase));       
   }
   
   public function addToCart(Varien_Event_Observer $observer)
   {
      $event = $observer->getEvent();
      $product = $product = $observer->getQuoteItem()->getProduct();
      $productArray = $this->createProductArray($product,1,$product->getFinalPrice());  
      Mage::getModel('customer/session')->setAddProduct(json_encode($productArray));           
   }
   
   public function removeFromCart(Varien_Event_Observer $observer)
   {
      $event = $observer->getEvent();
      $product = $product = $observer->getQuoteItem()->getProduct();
      $productArray = $this->createProductArray($product,$observer->getQuoteItem()->getQty(),$product->getFinalPrice());
      Mage::getModel('customer/session')->setRemoveProduct(json_encode($productArray));            
   }
   
   public function productView(Varien_Event_Observer $observer)
   {
      $event = $observer->getEvent();
      $product = $observer->getProduct();
      $productArray = $this->createProductArray($product);  
      Mage::getModel('customer/session')->setProductView(json_encode($productArray));          
   }
   
   public function productList(Varien_Event_Observer $observer)
   {
      $products = $observer->getEvent()->getCollection();
      $count = 0;
      $listProducts = [];
      foreach($products as $prod) {
         array_push($listProducts,$this->createProductArray($prod,null,null,$count));
      $count++;
      }
      Mage::getModel('customer/session')->setProductList($listProducts);          
   }
   
}  