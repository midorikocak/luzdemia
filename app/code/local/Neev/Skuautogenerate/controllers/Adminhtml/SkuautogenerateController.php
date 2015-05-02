<?php

class Neev_Skuautogenerate_Adminhtml_SkuautogenerateController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('skuautogenerate/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Rules Manager'), Mage::helper('adminhtml')->__('Rule Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('skuautogenerate/skuautogenerate')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('skuautogenerate_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('skuautogenerate/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Rule Manager'), Mage::helper('adminhtml')->__('Rule Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Rule News'), Mage::helper('adminhtml')->__('Rule News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('skuautogenerate/adminhtml_skuautogenerate_edit'))
				->_addLeft($this->getLayout()->createBlock('skuautogenerate/adminhtml_skuautogenerate_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('skuautogenerate')->__('Rule does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
	$dbread = Mage::getSingleton('core/resource')->getConnection('core_read');
    $sql = $dbread->query("SELECT skuautogenerate_id FROM skuautogenerate WHERE product_type='".$this->getRequest()->getPost('product_type')."'");
    $res = $sql->fetch();
	  		$errorMessage="Sku Auto genration setting for ".$this->getRequest()->getPost('product_type')." product type is allready having,Please edit the ".$res["skuautogenerate_id"]." id record";	
			
			$model = Mage::getModel('skuautogenerate/skuautogenerate');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				if ($res["skuautogenerate_id"] && $res["skuautogenerate_id"]!=$this->getRequest()->getParam('id')) {
                 throw new Exception($errorMessage);
                 $this->_redirect('*/*/'); }
                 else {
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('skuautogenerate')->__('Rule was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);}

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('skuautogenerate')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('skuautogenerate/skuautogenerate');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $skuautogenerateIds = $this->getRequest()->getParam('skuautogenerate');
        if(!is_array($skuautogenerateIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($skuautogenerateIds as $skuautogenerateId) {
                    $skuautogenerate = Mage::getModel('skuautogenerate/skuautogenerate')->load($skuautogenerateId);
                    $skuautogenerate->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($skuautogenerateIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $skuautogenerateIds = $this->getRequest()->getParam('skuautogenerate');
        if(!is_array($skuautogenerateIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($skuautogenerateIds as $skuautogenerateId) {
                    $skuautogenerate = Mage::getSingleton('skuautogenerate/skuautogenerate')
                        ->load($skuautogenerateId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($skuautogenerateIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'skuautogenerate.csv';
        $content    = $this->getLayout()->createBlock('skuautogenerate/adminhtml_skuautogenerate_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'skuautogenerate.xml';
        $content    = $this->getLayout()->createBlock('skuautogenerate/adminhtml_skuautogenerate_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}