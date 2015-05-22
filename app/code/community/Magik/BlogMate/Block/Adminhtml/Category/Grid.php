<?php

class Magik_BlogMate_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	public function __construct()
	{
		parent::__construct();
		$this->setId("categoryGrid");
		$this->setDefaultSort("id");
		$this->setDefaultDir("ASC");
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel("blogmate/category")->getCollection();
		//$collection->addFieldToFilter('id', array('neq' => '1'));
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		$this->addColumn("id", array(
			"header" => Mage::helper("blogmate")->__("ID"),
			"align"  => "right",
			"width"  => "50px",
			"type"   => "number",
			"index"  => "id",
			));

		$this->addColumn("title", array(
			"header" => Mage::helper("blogmate")->__("Title"),
			"index"  => "title",
			));

		$this->addColumn("short_description", array(
			"header" => Mage::helper("blogmate")->__("Short Description"),
			"index"  => "short_description",
			));


		$this->addColumn("display_order", array(
		                 "header" => Mage::helper("blogmate")->__("Display Order"),
		                 "width"  => "120px",
		                 "index" => "display_order",
		                 ));

		/*$this->addColumn('status', array(
		                 'header'  => Mage::helper('blogmate')->__('Status'),
		                 'index'   => 'status',
		                 'type'    => 'options',
		                 'options' => Magik_BlogMate_Block_Adminhtml_Category_Grid::getOptionArray8(),				
		                 ));*/
		$this->addColumn("status", array(
				      "header"    => Mage::helper("blogmate")->__("Status"),
				      "align"     => "left",
				      "width"     => "80px",
				      "index"     => "status",
				      "type"      => "options",
				      "options"   => array(
					  1 => "Enabled",
					  2 => "Disabled",
				      ),
				  ));

		
		$this->addRssList('blogmate/adminhtml_rss_rss/category', Mage::helper('blogmate')->__('RSS'));
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
		$this->getMassactionBlock()->addItem('remove_category', array(
			'label'   => Mage::helper('blogmate')->__('Remove Category'),
			'url'     => $this->getUrl('*/adminhtml_category/massRemove'),
			'confirm' => Mage::helper('blogmate')->__('Are you sure?')
			));
		return $this;
	}

	static public function getOptionArray8() {
		$data_array=array();
		$data_array[1] = 'Enable';
		$data_array[0] = 'Disable';
		return($data_array);
	}

	/*static public function getValueArray8() {
		$data_array=array();
		foreach(Magik_FAQmate_Block_Adminhtml_Faqdetail_Grid::getOptionArray8() as $k=>$v){
			$data_array[]=array('value' => $k,'label' => $v);		
		}
		return($data_array);
	}*/


}