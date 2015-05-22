<?php

class Magik_Socialbar_Block_Adminhtml_Socialbar_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("socialbarGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		/*protected function _prepareCollection()
		{
				$collection = Mage::getModel("socialbar/socialbar")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}*/

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("socialbar/socialbar")->getCollection();
				foreach($collection as $link){
				    if($link->getStoreId() && $link->getStoreId() != 0 ){
					$link->setStoreId(explode(',',$link->getStoreId()));
				    }
				    else{
					$link->setStoreId(array('0'));
				    }
				}
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}

		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("socialbar")->__("ID"),
				"align" =>"right",
				"width" => "50px",
				"type" => "number",
				"index" => "id",
				));
                
				$this->addColumn('title', array(
				    'header'    => Mage::helper('socialbar')->__('Name'),
				    'align'     =>'left',
				    'index'     => 'name',
				));

				$this->addColumn("social_block_code", array(
				"header" => Mage::helper("socialbar")->__("Block Code"),
				"align" =>"left",
				"index" => "social_block_code",
				"width" => "100px",
				));

				$this->addColumn("store_id", array(
				"header"        => Mage::helper("socialbar")->__("Store View"),
				"index"        => "store_id",
				"type"          => "store",
				"store_all"     => true,
				"store_view"    => true,
				"sortable"      => true,
				"filter_condition_callback" => array($this,
				    "_filterStoreCondition"),
				));

				$this->addColumn('action',
				    array(
					'header'    =>  Mage::helper('socialbar')->__('Action'),
					'width'     => '100',
					'type'      => 'action',
					'getter'    => 'getId',
					'actions'   => array(
					    array(
						'caption'   => Mage::helper('socialbar')->__('Edit'),
						'url'       => array('base'=> '*/*/edit'),
						'field'     => 'id'
					    )
					),
					'filter'    => false,
					'sortable'  => false,
					'index'     => 'stores',
					'is_system' => true,
				));

			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_socialbar', array(
					 'label'=> Mage::helper('socialbar')->__('Remove Socialbar'),
					 'url'  => $this->getUrl('*/adminhtml_socialbar/massRemove'),
					 'confirm' => Mage::helper('socialbar')->__('Are you sure?')
				));
			return $this;
		}
			

}