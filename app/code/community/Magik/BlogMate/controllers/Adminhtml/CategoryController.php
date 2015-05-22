<?php

class Magik_BlogMate_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("blogmate/category")->_addBreadcrumb(Mage::helper("adminhtml")->__("Category  Manager"),Mage::helper("adminhtml")->__("Category Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("BlogMate"));
			    $this->_title($this->__("Manager Category"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("BlogMate"));
				$this->_title($this->__("Category"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("blogmate/category")->load($id);
				if ($model->getId()) {
					Mage::register("category_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("blogmate/category");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Category Manager"), Mage::helper("adminhtml")->__("Category Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Category Description"), Mage::helper("adminhtml")->__("Category Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("blogmate/adminhtml_category_edit"))->_addLeft($this->getLayout()->createBlock("blogmate/adminhtml_category_edit_tabs"));
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
		$this->_title($this->__("Category"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("blogmate/category")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("category_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("blogmate/category");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Category Manager"), Mage::helper("adminhtml")->__("Category Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Category Description"), Mage::helper("adminhtml")->__("Category Description"));


		$this->_addContent($this->getLayout()->createBlock("blogmate/adminhtml_category_edit"))->_addLeft($this->getLayout()->createBlock("blogmate/adminhtml_category_edit_tabs"));

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

						$model = Mage::getModel("blogmate/category")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Category was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setCategoryData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setCategoryData($this->getRequest()->getPost());
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
						$model = Mage::getModel("blogmate/category");
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
                      $model = Mage::getModel("blogmate/category");
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
			$fileName   = 'category.csv';
			$grid       = $this->getLayout()->createBlock('blogmate/adminhtml_category_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'category.xml';
			$grid       = $this->getLayout()->createBlock('blogmate/adminhtml_category_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
		
	      public function subcategoryAction() {
		  $parentid = $this->getRequest()->getParam('cat_pid');
		  $subcategory = "<option value=''>Please Select</option>";
		  if ($parentid != '') {
		    
			$subcategoryarray =Mage::getModel('blogmate/category')
			->getCollection()
			->addFieldToFilter('cat_pid',array('eq'=>$parentid))
			->addFieldToFilter('subcategory',array('eq'=>'0'))
			->load();
		      foreach ($subcategoryarray as $_subcategory) {
			  $subcategory .= "<option value='" . $_subcategory->getId() . "'>" .  $_subcategory->getTitle() . "</option>";
		      }
		  }
		  echo $subcategory;
	      }
}
