<?php

class Magik_BlogMate_Adminhtml_Rss_RssController extends Mage_Core_Controller_Front_Action
{
	
		public function blogAction()
		{

			//DEMO RSS CONTENT
            //app\design\frontend\base\default\layout\rss.xml
			/*
				<rss_order_new>
					<block type="rss/order_new" output="toHtml" name="rss.order.new"/>
				</rss_order_new>
			*/
			//app\code\core\Mage\Rss\Block\Order\New.php
			//DEMO RSS CONTENT


			$this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
			$this->loadLayout(false);
			$this->renderLayout();
		}
			
		public function categoryAction()
		{

			//DEMO RSS CONTENT
            //app\design\frontend\base\default\layout\rss.xml
			/*
				<rss_order_new>
					<block type="rss/order_new" output="toHtml" name="rss.order.new"/>
				</rss_order_new>
			*/
			//app\code\core\Mage\Rss\Block\Order\New.php
			//DEMO RSS CONTENT


			$this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
			$this->loadLayout(false);
			$this->renderLayout();
		}
			
    public function preDispatch()
    {
		
			if ($this->getRequest()->getActionName() == 'blog') {
				$this->_currentArea = 'adminhtml';
				Mage::helper('rss')->authAdmin('magik/blogmate');
			}
			
			if ($this->getRequest()->getActionName() == 'category') {
				$this->_currentArea = 'adminhtml';
				Mage::helper('rss')->authAdmin('magik/blogmate');
			}
			
        return parent::preDispatch();
    }
}
	
	