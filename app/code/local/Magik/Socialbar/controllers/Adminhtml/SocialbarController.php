<?php

class Magik_Socialbar_Adminhtml_SocialbarController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("socialbar/socialbar")->_addBreadcrumb(Mage::helper("adminhtml")->__("Socialbar  Manager"),Mage::helper("adminhtml")->__("Socialbar Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Socialbar"));
			    $this->_title($this->__("Manager Socialbar"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Socialbar"));
				$this->_title($this->__("Socialbar"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("socialbar/socialbar")->load($id);
				if ($model->getId()) {
					Mage::register("socialbar_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("socialbar/socialbar");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Socialbar Manager"), Mage::helper("adminhtml")->__("Socialbar Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Socialbar Description"), Mage::helper("adminhtml")->__("Socialbar Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("socialbar/adminhtml_socialbar_edit"))->_addLeft($this->getLayout()->createBlock("socialbar/adminhtml_socialbar_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("socialbar")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Socialbar"));
		$this->_title($this->__("Socialbar"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("socialbar/socialbar")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("socialbar_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("socialbar/socialbar");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Socialbar Manager"), Mage::helper("adminhtml")->__("Socialbar Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Socialbar Description"), Mage::helper("adminhtml")->__("Socialbar Description"));


		$this->_addContent($this->getLayout()->createBlock("socialbar/adminhtml_socialbar_edit"))->_addLeft($this->getLayout()->createBlock("socialbar/adminhtml_socialbar_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						if(count($post_data['show_socialsites']) > 0){
							for($y=0;$y<=count($post_data['show_socialsites']);$y++){
							      $finalselected_sites=implode(",",$post_data['show_socialsites']);
							}
							    $post_data['show_socialsites']=$finalselected_sites;
						}else{ 
							    
							    $post_data['show_socialsites'] = ''; 
						    }


						if($post_data['show_pagelocation'] == 'chkcategorylevel'){
						    if(count($post_data['show_category']) > 0){
							for($k=0;$k<=count($post_data['show_category']);$k++){
							      $finalselected_category=implode(",",$post_data['show_category']);
							}
							$post_data['show_category']=$finalselected_category;
						    }else{ $post_data['show_pagelocation'] = '';
							    $post_data['show_category'] = ''; }
						}else{ 	
							$post_data['show_category'] = ''; } 

						if(count($post_data['store_id']) > 0){
							  for($j=0;$j<=count($post_data['store_id']);$j++){
							    $finalselected_store=implode(",",$post_data['store_id']);
							   }
							  $post_data['store_id']=$finalselected_store;
						}
						  
						

						$model = Mage::getModel("socialbar/socialbar")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						$reqID=$model->getId();
						$social_code='$this->getLayout()->createBlock("core/template")->setTemplate("magik/socialbar/socialbar.phtml")->setBlockId('.$reqID.')->toHtml();';
						$getsocialCode="<?php echo ".$social_code." ?>";   						  
						$resource = Mage::getSingleton('core/resource');
						$write= $resource->getConnection('core_write');
						$ageTable = $resource->getTableName('magik_socialbar'); 
						$Update_social_code="Update $ageTable SET social_block_code='".$getsocialCode."' WHERE id ='".$reqID."'";	
						$write->query($Update_social_code);  

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Socialbar was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setSocialbarData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setSocialbarData($this->getRequest()->getPost());
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
						$model = Mage::getModel("socialbar/socialbar");
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
                      $model = Mage::getModel("socialbar/socialbar");
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
			$fileName   = 'socialbar.csv';
			$grid       = $this->getLayout()->createBlock('socialbar/adminhtml_socialbar_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'socialbar.xml';
			$grid       = $this->getLayout()->createBlock('socialbar/adminhtml_socialbar_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
