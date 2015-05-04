<?php
require_once 'app/Mage.php';
Mage::app('default');

class SkuFresh{
   
   private $products;
   
   public function __construct(){
      
      $this->products = Mage::getModel('catalog/product')->getCollection();
   }
   
   public function checkSku($sku){
          $this->products->addAttributeToFilter('sku', array('eq' => $sku));
          if($this->products->getSize() == 0){
             return true;
          }else{
             return false;
          }
   }
   
   public function createSku($prefix,$amount){
      $result = [];
      
      $latestSku = $this->getLatestSku($prefix);
      $latestNumber = $this->getSkuDetails($latestSku);
      
      for($i=1;$i<=$amount;$i++){
         
         $newNumber = $latestNumber + $i;
         $newSku = $prefix.$newNumber;

         if($this->checkSku($newSku)){
            array_push($result,$newSku);
         }
         else{
            $amount + 1;
         }
      }
      return $result;
   }
   
   public function getSkuDetails($sku){
      preg_match("/(?<prefix>[A-Z][A-Z])(?<number>\d+)/", $sku, $matches);
      return $matches['number'];
   }
   
   public function getLatestSku($prefix){
      $this->products->addAttributeToFilter('sku', array('regexp' => '^'.$prefix.'[0-9]+$'));
      $products = $this->products->setOrder('sku', 'desc');
      return $this->products->getFirstItem()->getSku();
   }
   
   
   
}


try{
   
   Mage::getSingleton('core/session', array('name'=>'adminhtml'));

   //verify if the user is logged in to the backend
   if(Mage::getSingleton('admin/session')->isLoggedIn()){
      if(isset($_POST['prefix'])){
         $freshSku = new SkuFresh();
         foreach($freshSku->createSku(htmlspecialchars($_POST['prefix']),htmlspecialchars($_POST['amount'])) as $newSku){
            echo $newSku.'<br/>';
         }
      }else{
         ?>
         
         <!doctype html>
         <html class="no-js" lang="">
             <head>
                 <meta charset="utf-8">
                 <meta http-equiv="x-ua-compatible" content="ie=edge">
                 <title>Fresh Sku Generator By Midori Kocak</title>
                 <meta name="description" content="">
                 <meta name="viewport" content="width=device-width, initial-scale=1">

             </head>
             <body>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
               <label for="prefix">Prefix</label>
                <input type="text" name="prefix" id="prefix"><br/><br/>
                <label for="amount">Amount</label>
                 <input type="text" name="amount" id="amount"><br/><br/>
                <input type="submit" value="Submit">
                </form>
             </body>
         </html>
         
         <?php
      }
   }
   else
   {
     echo "You are not authorized to view this page! Midori is angry!";
   }
      
}
catch(Exception $e){
   die($e->getMessage());
}