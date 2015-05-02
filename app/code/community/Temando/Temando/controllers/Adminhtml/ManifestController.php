<?php

class Temando_Temando_Adminhtml_ManifestController extends Mage_Adminhtml_Controller_Action
{
    
    public function indexAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('temando/manifest')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Manifests'), Mage::helper('adminhtml')->__('Manage Manifests'))
            ->renderLayout();
    }

    /**
     * Convert dates in array from localized to internal format
     *
     * @param   array $array
     * @param   array $dateFields
     * @return  array
     */
    protected function _filterDates($array, $dateFields)
    {
        if (empty($dateFields)) {
            return $array;
        }
        $filterInput = new Zend_Filter_LocalizedToNormalized(array(
            'date_format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));
        $filterInternal = new Zend_Filter_NormalizedToLocalized(array(
            'date_format' => Varien_Date::DATE_INTERNAL_FORMAT
        ));

        foreach ($dateFields as $dateField) {
            if (array_key_exists($dateField, $array) && !empty($dateField)) {
                $array[$dateField] = $filterInput->filter($array[$dateField]);
                $array[$dateField] = $filterInternal->filter($array[$dateField]);
            }
        }
        return $array;
    }
    
    public function addAction()
    {
        $postData = $this->getRequest()->getPost();
        $post = $this->_filterDates($postData, array('from', 'to'));
        $carriers = $this->getRequest()->getParam('carrier_id');
        if (!is_array($carriers)) {
            $carriers = array($carriers);
        }

        $carriers_options = Mage::getModel('temando/shipping_carrier_temando_source_method')->getOptions();
        foreach ($carriers as $carrierId) {
	    
	    if(!$carrierId)
		continue;
	    
            $request = array(
                'carrierId' => $carrierId,
                'location'  => $this->getRequest()->getParam('warehouse_id'),
                'readyDate' => isset($post['from'])?$post['from']:'',
                'type'      => 'Awaiting Confirmation',
                'labelPrinterType' => Mage::helper('temando')->getConfigData('options/label_type')
            );

            try {
                $api = Mage::getModel('temando/api_client');
		/* @var $api Temando_Temando_Model_Api_Client */
                $api->connect(
                    Mage::helper('temando')->getConfigData('general/username'),
                    Mage::helper('temando')->getConfigData('general/password'),
                    Mage::helper('temando')->getConfigData('general/sandbox'));
                $result = $api->getManifest($request);
                if (!$result) {
                    throw new Exception('Cannot send request');
                }

                $model = Mage::getModel('temando/manifest')
                    ->setData('location_id', $this->getRequest()->getParam('warehouse_id'))
                    ->setData('carrier_id', $carrierId)
                    ->setData('start_date', isset($post['from'])?$post['from']:'')
                    ->setData('end_date', isset($post['from'])?$post['from']:'')
                    ->setData('manifest_document_type', '')
                    ->setData('manifest_document', '')
                    ->setData('label_document_type', '')
                    ->setData('type', 'Awaiting Confirmation')
                    ->setData('label_document', '')
                    ;

                $carrier_name = $carriers_options[$carrierId];
                if (!isset($result->manifestDocument) || !((string)$result->manifestDocument)) {
                    throw new Exception('No data for carrier: ' . $carrier_name);
                }

                if (isset($result->manifestDocumentType)) {
                    $model->setData('manifest_document_type', (string)$result->manifestDocumentType);
                }
                if (isset($result->manifestDocument)) {
                    $model->setData('manifest_document', (string)$result->manifestDocument);
                }
                if (isset($result->labelDocumentType)) {
                    $model->setData('label_document_type', (string)$result->labelDocumentType);
                }
                if (isset($result->labelDocument)) {
                    $model->setData('label_document', (string)$result->labelDocument);
                }

                $model->save();
                $this->_getSession()
                        ->addSuccess($this->__('Manifest successful added for carrier: ') . $carrier_name);
            } catch (Exception $e) {
                $this->_getSession()
                        ->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/');
    }
    
    public function manifestAction()
    {
        $manifest = Mage::getModel('temando/manifest')
            ->load($this->getRequest()->getParam('id'));
        /* @var $manifest Temando_Temando_Model_Manifest */
        if ($manifest->getId()) {
            $document = base64_decode($manifest->getManifestDocument());
            $document_length = strlen($document);
            $extension = '.pdf';
            $_t = explode('/', $manifest->getManifestDocumentType());
            if (isset($_t[1])) {
                $extension = "." . $_t[1];
            }

            if ($document_length) {
                $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->setHeader('Pragma', 'public', true)
                    ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
                    ->setHeader('Content-type', $manifest->getManifestDocumentType(), true)
                    ->setHeader('Content-Length', $document_length)
                    ->setHeader('Content-Disposition', 'attachment; filename="manifest-'.$manifest->getId().$extension.'"')
                    ->setHeader('Last-Modified', date('r'));
                $this->getResponse()->clearBody();
                $this->getResponse()->sendHeaders();
                print $document;
            }
        }
    }

    public function labelAction()
    {
        $manifest = Mage::getModel('temando/manifest')
            ->load($this->getRequest()->getParam('id'));
        /* @var $manifest Temando_Temando_Model_Manifest */
        if ($manifest->getId()) {
            $document = base64_decode($manifest->getLabelDocument());
            $document_length = strlen($document);
            $extension = '.pdf';
            $_t = explode('/', $manifest->getLabelDocumentType());
            if (isset($_t[1])) {
                $extension = "." . $_t[1];
            }

            if ($document_length) {
                $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->setHeader('Pragma', 'public', true)
                    ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
                    ->setHeader('Content-type', $manifest->getLabelDocumentType(), true)
                    ->setHeader('Content-Length', $document_length)
                    ->setHeader('Content-Disposition', 'attachment; filename="label-'.$manifest->getId().$extension.'"')
                    ->setHeader('Last-Modified', date('r'));
                $this->getResponse()->clearBody();
                $this->getResponse()->sendHeaders();
                print $document;
            }
        }
    }

    public function confirmAction()
    {
        $manifests = Mage::getModel('temando/manifest')
            ->getCollection()->addFieldToFilter('type', 'Awaiting Confirmation')->getItems();
        $carriers_options = Mage::getModel('temando/shipping_carrier_temando_source_method')->getOptions();
        foreach ($manifests as $manifest) {
            $request = array(
                'carrierId' => $manifest->getCarrierId(),
                'location'  => $manifest->getLocationId(),
                'startReadyDate' => $manifest->getStartDate(),
                'endReadyDate' => $manifest->getStartDate(),
                /* @todo use store date, not server date */
                'confirmedReadyDate' => now(true),
                'labelPrinterType' => Mage::helper('temando')->getConfigData('options/label_type')
            );

            try {
                $api = Mage::getModel('temando/api_client');
                $api->connect(
                    Mage::helper('temando')->getConfigData('general/username'),
                    Mage::helper('temando')->getConfigData('general/password'),
                    Mage::helper('temando')->getConfigData('general/sandbox'),
                    true);
                $result = $api->confirmManifest($request);
                if (!$result) {
                    throw new Exception('Cannot send request');
                }

                $carrier_name = $carriers_options[$manifest->getCarrierId()];
                if (!isset($result->manifestDocument) || !((string)$result->manifestDocument)) {
                    throw new Exception('No data for carrier: ' . $carrier_name . 'and date ' . $manifest->getStartDate());
                }

                if (isset($result->manifestDocumentType)) {
                    $manifest->setData('manifest_document_type', (string)$result->manifestDocumentType);
                }
                if (isset($result->manifestDocument)) {
                    $manifest->setData('manifest_document', (string)$result->manifestDocument);
                }
                if (isset($result->labelDocumentType)) {
                    $manifest->setData('label_document_type', (string)$result->labelDocumentType);
                }
                if (isset($result->labelDocument)) {
                    $manifest->setData('label_document', (string)$result->labelDocument);
                }

                $manifest->setData('type', 'Confirmed');
                $manifest->save();
                $this->_getSession()
                        ->addSuccess($this->__('Manifest successful confirmed for carrier: ' . $carrier_name . ' and date ' . $manifest->getStartDate()));
            } catch (Exception $e) {
                $this->_getSession()
                        ->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/');
    }
    
}
