<?php
class Neev_Skuautogenerate_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/skuautogenerate?id=15 
    	 *  or
    	 * http://site.com/skuautogenerate/id/15 	
    	 */
    	/* 
		$skuautogenerate_id = $this->getRequest()->getParam('id');

  		if($skuautogenerate_id != null && $skuautogenerate_id != '')	{
			$skuautogenerate = Mage::getModel('skuautogenerate/skuautogenerate')->load($skuautogenerate_id)->getData();
		} else {
			$skuautogenerate = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($skuautogenerate == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$skuautogenerateTable = $resource->getTableName('skuautogenerate');
			
			$select = $read->select()
			   ->from($skuautogenerateTable,array('skuautogenerate_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$skuautogenerate = $read->fetchRow($select);
		}
		Mage::register('skuautogenerate', $skuautogenerate);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}