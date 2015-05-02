<?php

class Temando_Temando_PcsController extends Mage_Core_Controller_Front_Action
{
    /**
     * @var Temando_Temando_Model_Pcs 
     */
    protected $_validator = null;
    
    public function testAction()
    {
	$shippingMethod = 'temando_flat';
	$selected_quote_id = preg_replace('#^[^_]*_#', '', $shippingMethod);
	
	echo Mage::helper('temando')->isQuoteDynamic($selected_quote_id) ?
		'DYNAMIC' : 'FREE/FLAT';
    }
    
    private $_result = array (
            'query' => '',
            'suggestions' => array(),
            'data' => array(
                0 => array (
                    0 => array(
                            'city' => '',
                            'region_id' => '',
                            'postcode' => ''
                        )
                    )
            )
        );
    
    public function construct() {
        parent:: construct();
        
        $this->loadLayout();
        $this->renderLayout();
    }
    
    protected function _makeAutocomplete($query, $country = 'AU')
    {       
        $this->_result['query'] = Mage::helper('core')->escapeHtml($query);
        
	$this->_getValidator();
	$this->_validator->setCountry($country)->setQuery($query);
	
	$suggestions = $this->_validator->getSuggestions();
	
	$i = -1;
	if(!empty($suggestions)) {
	    //have results - load into result array
	    $this->_result['data'] = array();
	    foreach($suggestions as $item)
	    {
		$fulltext = $item['name'] .', ';
		$fulltext.= array_key_exists('postcodes', $item) && !empty($item['postcodes']) ? $item['postcodes'][0]['code'].' ' : ' ';
		$fulltext.= $item['country']['iso_code2'];
		
		if (!in_array($fulltext, $this->_result['suggestions'])) {
		    $i++; $this->_result['suggestions'][$i] = $fulltext;
		}
		$this->_result['data'][$i][] = array(
		    'postcode'	=> array_key_exists('postcodes', $item) && !empty($item['postcodes']) ? $item['postcodes'][0]['code'] : '',
		    'city'	=> $item['name'],
		    'country_id'=> $item['country']['iso_code2'],
		    'fulltext'	=> $fulltext
		);
	    }
	}
        $core_helper = Mage::helper('core');
        if (method_exists($core_helper, "jsonEncode")) {
            $result = Mage::helper('core')->jsonEncode($this->_result);
        } else {
            $result = Zend_Json::encode($this->_result);
        }

        return $result;
    }
    
    public function autocompletecartAction() {
        
        $query = $this->getRequest()->getParam('query');
	$country = $this->getRequest()->getParam('country','AU');
	
	echo $this->_makeAutocomplete($query, $country);
        exit;
    }

    public function generateAction()
    {
        $array1 = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $array2 = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
                        'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
                        'u', 'v', 'w', 'x', 'y', 'z');

        foreach ($array1 as $c) {
            $this->_makeAutocomplete($c);
        }

        foreach ($array1 as $c1) {
            foreach ($array1 as $c2) {
                $this->_makeAutocomplete($c1 . $c2);
            }
        }

        foreach ($array2 as $c) {
            $this->_makeAutocomplete($c);
        }

        /*foreach ($array2 as $c1) {
            foreach ($array2 as $c2) {
                $this->_makeAutocomplete($c1 . $c2);
            }
        }*/

        echo 'done'; exit;
    }


    public function productAction()
    {
        try {
            $data = array(
                'country_id' => $this->getRequest()->getParam('country_id'),
                'city' => $this->getRequest()->getParam('city'),
                'postcode' => $this->getRequest()->getParam('postcode'),
            );
            Mage::getSingleton('customer/session')->setData('estimate_product_shipping', new Varien_Object($data));
            $product_id = $this->getRequest()->getParam('product_id');
            $product = Mage::getModel('catalog/product')->load($product_id);
            $options = array();
            foreach (explode(';', $this->getRequest()->getParam('options')) as $o) {
                if (!$o) {
                    continue;
                }

                $_t = explode(':', $o);
                if (isset($_t[1])) {
                    $options[$_t[0]] = $_t[1];
                }
            }
            $quote = Mage::getModel('sales/quote');
            $request = array('qty' => $this->getRequest()->getParam('qty'));
            if (count($options)) {
                $request['super_attribute'] = $options;
            }

            $options = array();
            foreach (explode(';', $this->getRequest()->getParam('pr_options')) as $o) {
                if (!$o) {
                    continue;
                }

                $_t = explode(':', $o);
                if (isset($_t[1])) {
                    $options[$_t[0]] = $_t[1];
                }
            }
	    
	    //get bundle products options
	    $bundle_options = array();
	    if($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE)
	    {
		foreach (explode(';', $this->getRequest()->getParam('bl_options')) as $o) {
		    if (!$o) {
			continue;
		    }

		    $_t = explode(':', $o);
		    if (isset($_t[1])) {
			$bundle_options[$_t[0]] = $_t[1];
		    }
		}
		if(empty($bundle_options)) { //assign default values
		    $selectionCollection = $product->getTypeInstance(true)->getSelectionsCollection(
			$product->getTypeInstance(true)->getOptionsIds($product), $product
		    );

		    foreach($selectionCollection as $option) {
			if($option->getIsDefault() == true) {
			    $bundle_options[$option->getOptionId()] = $option->getSelectionId();
			}
		    }
		}
	    }
	    
            if (count($options)) {
                $request['options'] = $options;
            } 
	    
	    if(count($bundle_options)) {
		$request['bundle_option'] = $bundle_options;
	    }

            $item = $quote->addProduct($product, new Varien_Object($request));
            if (!is_object($item)) {
                throw new Exception(Mage::helper('temando')->__('Cannot calculate shipping cost for separate item'));
            }
            $item->setStoreId(Mage::app()->getStore()->getId());
            $item->setQty($this->getRequest()->getParam('qty'));
            $item->setPrice($product->getFinalPrice());
            $request = Mage::getModel('shipping/rate_request');
            foreach ($quote->getAllItems() as $i) {
                if (!$i->getPrice()) {
                    $i->setPrice($product->getFinalPrice());
                }
            }

            $request->setAllItems($quote->getAllItems());
            $request->setDestCountryId($this->getRequest()->getParam('country_id'));
            $request->setDestStreet('');
            $request->setDestCity($this->getRequest()->getParam('city'));
            $request->setDestPostcode($this->getRequest()->getParam('postcode'));
            $request->setPackageValue($item->getTotal());
            $request->setPackageValueWithDiscount($item->getTotal());
            $request->setPackageWeight($this->getRequest()->getParam('qty')*$product->getWeight());
            $request->setPackageQty($this->getRequest()->getParam('qty'));

            /**
             * Need for shipping methods that use insurance based on price of physical products
             */
            $request->setPackagePhysicalValue($item->getTotal());

            $request->setFreeMethodWeight($item);
            $request->setStoreId(Mage::app()->getStore()->getId());
            $request->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
            $request->setFreeShipping(null);
            /**
             * Currencies need to convert in free shipping
             */
            $request->setBaseCurrency(Mage::app()->getStore()->getBaseCurrency());
            $request->setPackageCurrency(Mage::app()->getStore()->getCurrentCurrency());
            $request->setLimitCarrier(null);
            $request->setOrig(false);
            $result = Mage::getModel('shipping/shipping')->collectCarrierRates('temando', $request)->getResult();
            Mage::register('product_rates', array('temando' => $result));
            $this->loadLayout();
            $this->renderLayout();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    protected function _getValidator()
    {
	if(!$this->_validator) {
	    $this->_validator = Mage::getModel('temando/pcs');
	}
	return $this;
    }
    
}
