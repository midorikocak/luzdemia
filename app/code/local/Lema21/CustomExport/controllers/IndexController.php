<?php


require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';

class Lema21_CustomExport_IndexController 
    extends Mage_Adminhtml_Sales_OrderController {

        const FILENAME = 'custom_orders.csv';

        public function indexAction()
        {
            
            $post = $this->getRequest()->getPost();

            $orderIdsList = $post['order_ids'];

             $serviceGenerateCSV = new Lema21_CustomExport_Service_GenerateCSV($orderIdsList);
             $contentCSV = $serviceGenerateCSV->call();

             $this->_prepareDownloadResponse(self::FILENAME, $contentCSV);               
        }
    }