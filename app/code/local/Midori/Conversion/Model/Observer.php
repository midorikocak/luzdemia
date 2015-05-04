<?php
class Midori_Conversion_Model_Observer
{
   
   public $brand = "luzio";
   
   private function createProductArray($product, $quantity = null, $price = null, $position = null, $list = null){
      $categoriesList = "";
      $cats = $product->getCategoryIds();
      
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
         'id'=>$product->getSku(),
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
      Mage::getModel('customer/session')->setUserRegisterId($customer->getId()); 
      Mage::getModel('customer/session')->setUserRegister(true); 
   }
   
   public function userLogin(Varien_Event_Observer $observer)
   {
      $event = $observer->getEvent();
      $customer = $event->getCustomer();
      $email = $customer->getId();
      Mage::getModel('customer/session')->setUserLoginId($customer->getId()); 
      Mage::getModel('customer/session')->setUserLogin(true); 
   }
   
   public function purchaseProduct(Varien_Event_Observer $observer)
   {
      Mage::getModel('customer/session')->setPurchaseProduct(true);       
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
      Mage::getModel('customer/session')->setProductView(true);          
   }
   
   public function productList(Varien_Event_Observer $observer)
   {
      Mage::getModel('customer/session')->setProductList(true);          
   }
   
}  