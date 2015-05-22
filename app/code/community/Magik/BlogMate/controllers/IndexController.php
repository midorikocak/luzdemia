<?php
class Magik_BlogMate_IndexController extends Mage_Core_Controller_Front_Action{
  public function IndexAction() {
    
   $this->loadLayout();   
   
   $blogmate_config = Mage::getStoreConfig('blogmate/blog_setting');
   if($blogmate_config['title']) $meta_title = $blogmate_config['title'];
   else $meta_title = 'Blog';
   $this->getLayout()->getBlock("head")->setTitle($this->__($meta_title));
   if($blogmate_config['description']) $this->getLayout()->getBlock("head")->setDescription($this->__($blogmate_config['description']));
   if($blogmate_config['keywords']) $this->getLayout()->getBlock("head")->setKeywords($this->__($blogmate_config['keywords']));
   if($blogmate_config['blogcrumbs']) {
    $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
    $breadcrumbs->addCrumb("home", array(
      "label" => $this->__("Home Page"),
      "title" => $this->__("Home Page"),
      "link"  => Mage::getBaseUrl()
      ));

    $breadcrumbs->addCrumb("blog", array(
      "label" => $this->__("Blog"),
      "title" => $this->__("Blog")
      ));
  }
  $this->renderLayout(); 
  
}
}