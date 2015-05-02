<?php

class Temando_Temando_Block_Adminhtml_Shipment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected $_shipment;
    
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'temando';
        $this->_controller = 'adminhtml_shipment';
        
        $this->removeButton('save');
        $this->removeButton('delete');

        $add_button_method = 'addButton';
        if (!method_exists($this, $add_button_method)) {
            $add_button_method = '_addButton';
        }

        $this->$add_button_method('disabled_pickslip', array(
	    'label'	=> Mage::helper('temando')->__('Pick Slip'),
	    'id'	=> 'disabled_pickslip',
	    'onclick'	=> 'return false',
	    'class'	=> 'go disabled',
            'title'     => Mage::helper('temando')->__('Available in the Business Extension')
	));
        
        if ($this->getShipment()->getStatus() == Temando_Temando_Model_System_Config_Source_Shipment_Status::PENDING) {
            $this->$add_button_method('getquote', array(
                'label' => Mage::helper('temando')->__('Save and Get Quotes'),
                'id' => 'getquote',
                'onclick' => 'saveAndGetQuotes()',
                'value' => '',
                'class' => 'save',
            ));
        }
        
        if ($this->getShipment()->getStatus() == Temando_Temando_Model_System_Config_Source_Shipment_Status::BOOKED) {
            $class = 'go';
            $title = Mage::helper('temando')->__('View Consignment');
            
	    $labelType = Mage::helper('temando')->getConfigData('options/label_type');
            $hasConsignment = true;
            
            if ((!$this->getShipment()->getConsignmentDocument() && $labelType == Temando_Temando_Model_System_Config_Source_Labeltype::STANDARD) || 
                    (!$this->getShipment()->getLabelDocument() && $labelType == Temando_Temando_Model_System_Config_Source_Labeltype::THERMAL)) {
                $class .= ' disabled';
                $hasConsignment = false;
                $title = Mage::helper('temando')->__('No Consignment');
            }
            
            $this->$add_button_method('consignment', array(
                'label' => $title,
                'id' => 'consignment',
                'onclick' => $hasConsignment ? "window.location = '" . $this->getUrl('*/*/consignment', array('id' => $this->getRequest()->getParam('id'))) . "'" : "",
                'value' => '',
                'class' => $class,
            ));
	    
	    $this->_removeButton('reset');
        }

        $max_boxes = 40;
        $script = "
            function saveAndGetQuotes() {
                editForm.submit($('edit_form').action+'and/getquotes/');
            }
            
            function saveAndEditNext() {
                editForm.submit($('edit_form').action+'and/editnext/');
            }
            
            function box_add() {
                num_boxes = $$('table#boxes tbody tr').length - 1;
                if (num_boxes < $max_boxes) {
                    if (!box_add.highest_num) {
                        box_add.highest_num = num_boxes;
                    }
                    // add a new box (row) to the table
                    new_row = new Element('tr');
                    new_row.id = 'box_row_' + ++box_add.highest_num;
                    new_row
                        .addClassName(box_add.highest_num % 2 ? 'odd' : 'even')
                        .addClassName('new_box')
                        .insert($('blank_box_row').innerHTML
                            .replace(/@@id@@/g, box_add.highest_num)
                            .replace(/box_blank/g, 'box'));
                    
                    $$('table#boxes tbody')[0].insert(new_row);
                    return box_add.highest_num;
                } else {
                    return false;
                }
            }
            
            function box_clear() {
                // remove all rows in the table.
                $$('table#boxes tr.existing_box, table#boxes tr.new_box').each(function (row) {
                    row.remove();
                });
            }
            
            function box_remove(num) {
                row = $('box_row_' + num);
                if ($('box_' + num + '_id') && $('boxes_deleted')) {
                    if (!$('boxes_deleted').value.length == 0) {
                        $('boxes_deleted').value += ',';
                    }
                    $('boxes_deleted').value += $('box_' + num + '_id').value;
                }
                if (row) {
                    row.remove();
                }
            }
            
            function box_import() {
                default_boxes = new Array();
                {$this->_getProductsJson('default_boxes')}
                for (var i = 0; i < default_boxes.length; i++) {
                    box = default_boxes[i];
                    num = box_add();
                    if (num) {
                        $('box_' + num + '_comment').value = box.comment;
                        $('box_' + num + '_packaging').value = box.packaging;
                        $('box_' + num + '_fragile').value = box.fragile;
                        $('box_' + num + '_qty').value = box.qty;
                        $('box_' + num + '_value').value = box.value;
                        $('box_' + num + '_weight').value = box.weight;
                        $('box_' + num + '_weight_unit').value = box.weight_unit;
                        $('box_' + num + '_height').value = box.height;
                        $('box_' + num + '_length').value = box.length;
                        $('box_' + num + '_width').value = box.width;
                        $('box_' + num + '_measure_unit').value = box.measure_unit;
                    }
                }
            }
        ";
        
        $this->_formScripts[] = $script;
    }
    
    /**
     * Gets the shipment being edited.
     *
     * @return Temando_Temando_Model_Shipment
     */
    public function getShipment()
    {
        if (!$this->_shipment) {
            $this->_shipment = Mage::registry('temando_shipment_data');
        }
        return $this->_shipment;
    }
    
    /**
     * Gets the current order for the shipment being edited.
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if($this->getShipment()) {
            return $this->getShipment()->getOrder();
        }
        return null;
    }

    public function getHeaderText()
    {
        if ($this->getShipment() && $this->getShipment()->getId()) {
            return $foo= Mage::helper('temando')->__(
                'Order # %s | %s',
                $this->htmlEscape($this->getShipment()->getOrder()->getRealOrderId()),
                $this->htmlEscape($this->formatDate($this->getShipment()->getOrder()->getCreatedAtDate(), 'medium', true))
            );
        }
    }
    
    protected function _getProductsJson($variable_name)
    {
        $products = '';
        foreach ($this->getShipment()->getOrder()->getAllItems() as $item) {
            if ($item->getParentItemId()) {
                // do not create box for child products
                continue;
            }
            /* @var $item Mage_Sales_Model_Order_Item */
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            /* @var $product Mage_Catalog_Model_Product */
            $helper = Mage::helper('temando');
            /* @var $helper Temando_Temando_Helper_Data */
            $weight = (float) $product->getWeight();
            $height = (float) $product->getTemandoHeight();
            $length = (float) $product->getTemandoLength();
            $width = (float) $product->getTemandoWidth();
            $packaging = $product->getTemandoPackaging();
            $fragile = $product->getTemandoFragile();
            
            $products .= "
                $variable_name.push({
                    'comment': '" . addslashes($product->getName()) . "',
                    'packaging': '{$packaging}',
                    'fragile': '{$fragile}',
                    'qty': '{$item->getQtyToShip()}',
                    'value': '{$item->getRowTotal()}',
                    'weight': '$weight',
                    'weight_unit': '{$helper->getConfigData('units/weight')}',
                    'height': '$height',
                    'length': '$length',
                    'width': '$width',
                    'measure_unit': '{$helper->getConfigData('units/measure')}'
                });
            ";
        }
        return $products;
    }
    
}
