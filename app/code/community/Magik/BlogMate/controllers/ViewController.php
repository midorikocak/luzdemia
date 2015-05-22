<?php
class Magik_BlogMate_ViewController extends Mage_Core_Controller_Front_Action{
  public function IndexAction() {

   $this->loadLayout();   
   $this->getLayout()->getBlock("head")->setTitle($this->__("Blog"));
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

   $this->renderLayout(); 

  }

  protected function _validateData($data) {
    $errors = array();

    $helper = Mage::helper('blogmate');

    if (!Zend_Validate::is($data->getUserName(), 'NotEmpty')) {
      $errors[] = $helper->__('Name can\'t be empty');
    }

    if (!Zend_Validate::is($data->getComment(), 'NotEmpty')) {
      $errors[] = $helper->__('Comment can\'t be empty');
    }

    if (!Zend_Validate::is($data->getBlogId(), 'NotEmpty')) {
      $errors[] = $helper->__('post_id can\'t be empty');
    }

    $validator = new Zend_Validate_EmailAddress();
    if (!$validator->isValid($data->getUserEmail())) {
      $errors[] = $helper->__('"%s" is not a valid email address.', $data->getEmail());
    }

    return $errors;
  }

  public function PostAction() {
    if ($data = $this->getRequest()->getPost()) {
      $model = Mage::getModel('blogmate/comment');
      $model->setData($data);
      $model->setComment(htmlspecialchars($model->getComment(), ENT_QUOTES));
      $session = Mage::getSingleton('customer/session');
      $errors = $this->_validateData($model);
      if (!empty($errors)) {
          $this->_redirectReferer();
          return;
      }
      $comment_config = Mage::getStoreConfig('blogmate/comment_setting');
      if($comment_config['approval']) {
        $model->setStatus(1);
      }
      elseif($session->isLoggedIn() && $comment_config['loginauto']) {
        $model->setStatus(1);
      }
      else {
        $model->setStatus(0);
      }
      $model->save();
    }

    $this->loadLayout();
    //$post_title_slug = $this->getRequest()->getParam('p');
    $currentUrl = Mage::helper('core/url')->getCurrentUrl();
    $post_title_slug = substr( $currentUrl, strrpos( $currentUrl, '/' )+1 );
    $post_controller_data = Mage::getModel('blogmate/blog')->getPostControllerData($post_title_slug);
    $this->getLayout()->getBlock("head")->setTitle($this->__("Blog / Post / ".ucwords($post_controller_data['title'])));
    $this->getLayout()->getBlock("head")->setDescription($this->__(ucwords($post_controller_data['meta_description'])));
    $this->getLayout()->getBlock("head")->setKeywords($this->__(ucwords($post_controller_data['meta_keywords'])));

    $blogmate_config = Mage::getStoreConfig('blogmate/blog_setting');
    if($blogmate_config['blogcrumbs']) {
      $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
        "label" => $this->__("Home Page"),
        "title" => $this->__("Home Page"),
        "link"  => Mage::getBaseUrl()
        ));

      $breadcrumbs->addCrumb("blog", array(
        "label" => $this->__("Blog"),
        "title" => $this->__("Blog"),
        "link"  => Mage::getBaseUrl().'blog/'
        ));
      $breadcrumbs->addCrumb("post", array(
      "label" => $this->__($post_controller_data['title']),
      "title" => $this->__($post_controller_data['title'])
      ));
    }

    $this->renderLayout(); 

  }
  
  public function CategoryAction() {
    $this->loadLayout();    
    //$category_title_slug = $this->getRequest()->getParam('c');
    $currentUrl = Mage::helper('core/url')->getCurrentUrl();
    $category_title_slug = substr( $currentUrl, strrpos( $currentUrl, '/' )+1 );
    $category_controller_data = Mage::getModel('blogmate/category')->getCategoryControllerData($category_title_slug);
    $this->getLayout()->getBlock("head")->setTitle($this->__("Blog / Category / ".ucwords($category_controller_data['title'])));
    $this->getLayout()->getBlock("head")->setDescription($this->__(ucwords($category_controller_data['meta_description'])));
    $this->getLayout()->getBlock("head")->setKeywords($this->__(ucwords($category_controller_data['meta_keywords'])));

    $blogmate_config = Mage::getStoreConfig('blogmate/blog_setting');
    
    if($blogmate_config['blogcrumbs']) {
      $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
        "label" => $this->__("Home Page"),
        "title" => $this->__("Home Page"),
        "link"  => Mage::getBaseUrl()
        ));

      $breadcrumbs->addCrumb("blog", array(
        "label" => $this->__("Blog"),
        "title" => $this->__("Blog"),
        "link"  => Mage::getBaseUrl().'blog/'
        ));
      
      $breadcrumbs->addCrumb("category", array(
        "label" => $this->__($category_controller_data['title']),
        "title" => $this->__($category_controller_data['title'])
        ));
    }
    $this->renderLayout(); 

  }

}