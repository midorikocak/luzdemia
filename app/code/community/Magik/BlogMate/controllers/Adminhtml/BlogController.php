<?php

class Magik_BlogMate_Adminhtml_BlogController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu("blogmate/blog")->_addBreadcrumb(Mage::helper("adminhtml")->__("Blog  Manager"),Mage::helper("adminhtml")->__("Blog Manager"));
		return $this;
	}
	public function indexAction() 
	{
		$this->_title($this->__("BlogMate"));
		$this->_title($this->__("Manager Blog"));

		$this->_initAction();
		$this->renderLayout();
	}
	public function editAction()
	{			    
		$this->_title($this->__("BlogMate"));
		$this->_title($this->__("Blog"));
		$this->_title($this->__("Edit Item"));



		$id = $this->getRequest()->getParam("id");
		$model = Mage::getModel("blogmate/blog")->load($id);
		if ($model->getId()) {
			Mage::register("blog_data", $model);

			$this->loadLayout();
			$this->_setActiveMenu("blogmate/blog");

			if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
				$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			}

			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Blog Manager"), Mage::helper("adminhtml")->__("Blog Manager"));
			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Blog Description"), Mage::helper("adminhtml")->__("Blog Description"));
			$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock("blogmate/adminhtml_blog_edit"))->_addLeft($this->getLayout()->createBlock("blogmate/adminhtml_blog_edit_tabs"));
			$this->renderLayout();
		} 
		else {
			Mage::getSingleton("adminhtml/session")->addError(Mage::helper("blogmate")->__("Item does not exist."));
			$this->_redirect("*/*/");
		}
	}

	public function newAction()
	{

		$this->_title($this->__("BlogMate"));
		$this->_title($this->__("Blog"));
		$this->_title($this->__("New Item"));

		$id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("blogmate/blog")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("blog_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("blogmate/blog");
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
				$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			}
		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Blog Manager"), Mage::helper("adminhtml")->__("Blog Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Blog Description"), Mage::helper("adminhtml")->__("Blog Description"));


		$this->_addContent($this->getLayout()->createBlock("blogmate/adminhtml_blog_edit"))->_addLeft($this->getLayout()->createBlock("blogmate/adminhtml_blog_edit_tabs"));

		$this->renderLayout();

	}
	public function saveAction()
	{

		$post_data=$this->getRequest()->getPost();


		if ($post_data) {

			try {

				foreach($post_data['stores_selected'] as $store) {
					$finalselected_store[] = $store;
				}
				$finalselected_store = implode(",",$finalselected_store);
				$post_data['stores_selected'] = $finalselected_store;


				foreach($post_data['categories_selected'] as $store) {
					$finalselected_categories[] = $store;
				}
				$finalselected_categories = implode(",",$finalselected_categories);
				$post_data['categories_selected'] = $finalselected_categories;

				$model = Mage::getModel("blogmate/blog")
				->addData($post_data)
				->setId($this->getRequest()->getParam("id"))
				->save();


				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Blog was successfully saved"));
				Mage::getSingleton("adminhtml/session")->setBlogData(false);

				if ($this->getRequest()->getParam("back")) {
					$this->_redirect("*/*/edit", array("id" => $model->getId()));
					return;
				}
				$this->_redirect("*/*/");
				return;
			} 
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
				Mage::getSingleton("adminhtml/session")->setBlogData($this->getRequest()->getPost());
				$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
				return;
			}

		}
		$this->_redirect("*/*/");
	}



	public function deleteAction()
	{
		if( $this->getRequest()->getParam("id") > 0 ) {
			try {
				$model = Mage::getModel("blogmate/blog");
				$model->setId($this->getRequest()->getParam("id"))->delete();
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
				$this->_redirect("*/*/");
			} 
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
				$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
			}
		}
		$this->_redirect("*/*/");
	}


	public function massRemoveAction()
	{
		try {
			$ids = $this->getRequest()->getPost('ids', array());
			foreach ($ids as $id) {
				$model = Mage::getModel("blogmate/blog");
				$model->setId($id)->delete();
			}
			Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
		}
		catch (Exception $e) {
			Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
		}
		$this->_redirect('*/*/');
	}

		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'blog.csv';
			$grid       = $this->getLayout()->createBlock('blogmate/adminhtml_blog_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'blog.xml';
			$grid       = $this->getLayout()->createBlock('blogmate/adminhtml_blog_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
	}
