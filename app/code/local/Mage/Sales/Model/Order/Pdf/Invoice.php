<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sales Order Invoice PDF model
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sales_Model_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Abstract
{
    /**
     * Draw header for item table
     *
     * @param Zend_Pdf_Page $page
     * @return void
     */
    protected function _drawHeader(Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 349, $this->y -15);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));

        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(399, $this->y, 723, $this->y -15);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));

        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(773, $this->y, 1097, $this->y -15);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));



        //columns headers
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('ÜRÜN'),
            'feed' => 35
        );
/*
        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('SKU'),
            'feed'  => 290,
            'align' => 'right'
        );
*/
        $lines[0][] = array(
            'text'  => strtoupper(Mage::helper('sales')->__('Qty')),
            'feed'  => 215,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('BİRİM FİYAT'),
            'feed'  => 275,
            'align' => 'right'
        );
/*
        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Tax'),
            'feed'  => 495,
            'align' => 'right'
        );
*/
        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('TUTAR'),
            'feed'  => 335,
            'align' => 'right'
        );

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 5
        );



        //2. Sayfa 374

    //columns headers
        $lines2[0][] = array(
            'text' => Mage::helper('sales')->__('ÜRÜN'),
            'feed' => 409
        );
/*
        $lines2[0][] = array(
            'text'  => Mage::helper('sales')->__('SKU'),
            'feed'  => 290,
            'align' => 'right'
        );
*/
        $lines2[0][] = array(
            'text'  => strtoupper(Mage::helper('sales')->__('Qty')),
            'feed'  => 589,
            'align' => 'right'
        );

        $lines2[0][] = array(
            'text'  => Mage::helper('sales')->__('BİRİM FİYAT'),
            'feed'  => 649,
            'align' => 'right'
        );
/*
        $lines2[0][] = array(
            'text'  => Mage::helper('sales')->__('Tax'),
            'feed'  => 495,
            'align' => 'right'
        );
*/
        $lines2[0][] = array(
            'text'  => Mage::helper('sales')->__('TUTAR'),
            'feed'  => 709,
            'align' => 'right'
        );

        $lineBlock2 = array(
            'lines'  => $lines2,
            'height' => 5
        );

        //3. Sayfa 748

         //columns headers
        $lines3[0][] = array(
            'text' => Mage::helper('sales')->__('ÜRÜN'),
            'feed' => 783
        );
/*
        $lines3[0][] = array(
            'text'  => Mage::helper('sales')->__('SKU'),
            'feed'  => 290,
            'align' => 'right'
        );
*/
        $lines3[0][] = array(
            'text'  => strtoupper(Mage::helper('sales')->__('Qty')),
            'feed'  => 963,
            'align' => 'right'
        );

        $lines3[0][] = array(
            'text'  => Mage::helper('sales')->__('BİRİM FİYAT'),
            'feed'  => 1023,
            'align' => 'right'
        );
/*
        $lines3[0][] = array(
            'text'  => Mage::helper('sales')->__('Tax'),
            'feed'  => 495,
            'align' => 'right'
        );
*/
        $lines3[0][] = array(
            'text'  => Mage::helper('sales')->__('TUTAR'),
            'feed'  => 1083,
            'align' => 'right'
        );

        $lineBlock3 = array(
            'lines'  => $lines3,
            'height' => 5
        );

        $tempY = $this->y;

        $this->y -= 10;

        $this->drawLineBlocks($page, array($lineBlock), array('table_header' => true));

        $this->y = $tempY;

        $this->y -= 10;
        
        $this->drawLineBlocks($page, array($lineBlock2), array('table_header' => true));

        $this->y = $tempY;

        $this->y -= 10;

        $this->drawLineBlocks($page, array($lineBlock3), array('table_header' => true));
        error_log("aaa3".$this->y);

        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

    /**
     * Return PDF document
     *
     * @param  array $invoices
     * @return Zend_Pdf
     */
    public function getPdf($invoices = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->emulate($invoice->getStoreId());
                Mage::app()->setCurrentStore($invoice->getStoreId());
            }
            $page  = $this->newPage();
            $order = $invoice->getOrder();
            /* Add image */
            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
		false
                //Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId())
            );
            /* Add document text and number */
/*            $this->insertDocumentNumber(
                $page,
                Mage::helper('sales')->__('Invoice # ') . $invoice->getIncrementId()
            );
*/
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($invoice->getAllItems() as $item){
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            /* Add totals */
            error_log("y1".$this->y);
            error_log("imp1".$this->yImp);
            $this->insertTotals($page, $invoice);
            error_log("y2".$this->y);
            error_log("imp2".$this->yImp);
            $this->insertTotals2($page, $invoice);
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->revert();
            }
        }
        $this->_afterGetPdf();
        return $pdf;
    }

    /**
     * Create new page and assign to PDF object
     *
     * @param  array $settings
     * @return Zend_Pdf_Page
     */
    public function newPage(array $settings = array())
    {
        /* Add new table head */
        $page = $this->_getPdf()->newPage("1122:793:");
        $this->_getPdf()->pages[] = $page;
        $this->y = 660;
        if (!empty($settings['table_header'])) {
            $this->_drawHeader($page);
        }
        return $page;
    }
}
