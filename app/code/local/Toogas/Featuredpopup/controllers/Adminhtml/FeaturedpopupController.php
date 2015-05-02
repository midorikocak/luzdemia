<?php
/**
 * Toogas Lda.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA (End-User License Agreement)
 * that is bundled with this package in the file toogas_license-free.txt.
 * It is also available at this URL:
 * http://www.toogas.com/licences/toogas_license-free.txt
 *
 * @category   Toogas
 * @package    Toogas_Featuredpopup
 * @copyright  Copyright (c) 2011 Toogas Lda. (http://www.toogas.com)
 * @license    http://www.toogas.com/licences/toogas_license-free.txt
 */
class Toogas_Featuredpopup_Adminhtml_FeaturedpopupController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
			
		
		$this->loadLayout()
			->_setActiveMenu('cms/featuredpopup') //ver isto
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('featuredpopup/featuredpopup')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('featuredpopup_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('cms/featuredpopup');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('featuredpopup/adminhtml_featuredpopup_edit'))
				->_addLeft($this->getLayout()->createBlock('featuredpopup/adminhtml_featuredpopup_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('featuredpopup')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
		
		if(isset($_FILES['image_link']['name']) and (file_exists($_FILES['image_link']['tmp_name']))) {
  			try {
    			$uploader = new Varien_File_Uploader('image_link');
    			$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
    			$uploader->setAllowRenameFiles(true);
    			$uploader->setFilesDispersion(false);
    			$path = Mage::getBaseDir('media') . DS .'toogas/featuredpopup';
    			$uploader->save($path, $_FILES['image_link']['name']);
    			$name_image_link = $uploader->getUploadedFileName();
    			$data['image_link'] = 'toogas/featuredpopup/' . $name_image_link;
    			//em cima acrescentei para ver se grava com path correcta
  				}catch(Exception $e) {
  			}
		}
      	else {

        	if(isset($data['image_link']['delete']) && $data['image_link']['delete'] == 1)
            	$data['image_link'] = '';
          	else
              	unset($data['image_link']);                    	
      	}
      		
	  				  			
			$model = Mage::getModel('featuredpopup/featuredpopup');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('featuredpopup')->__('The featured popup has been saved.'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('featuredpopup')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('featuredpopup/featuredpopup');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $featuredpopupsIds = $this->getRequest()->getParam('featuredpopup');
        if(!is_array($featuredpopupsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($featuredpopupsIds as $featuredpopupId) {
                    $featuredpopups = Mage::getModel('featuredpopup/featuredpopup')->load($featuredpopupId);
                    $featuredpopups->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($featuredpopupsIds)
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
        $featuredpopupsIds = $this->getRequest()->getParam('featuredpopup');
        if(!is_array($featuredpopupsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($featuredpopupsIds as $featuredpopupId) {
                    $featuredpopups = Mage::getSingleton('featuredpopup/featuredpopup')
                        ->load($featuredpopupId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($featuredpopupsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'featuredpopup.csv';
        $content    = $this->getLayout()->createBlock('featuredpopup/adminhtml_featuredpopup_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'featuredpopup.xml';
        $content    = $this->getLayout()->createBlock('featuredpopup/adminhtml_featuredpopup_grid')
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
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/cms/featuredpopup'); //ver isto muito bem
    }
    
    
}