<?php

class Magik_BlogMate_Block_Adminhtml_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	public function __construct()
	{
		parent::__construct();
		$this->setId("commentGrid");
		$this->setDefaultSort("id");
		$this->setDefaultDir("ASC");
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel("blogmate/comment")->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		$this->addColumn("id", array(
			"header" => Mage::helper("blogmate")->__("ID"),
			"align"  => "right",
			"width"  => "50px",
			"type" => "number",
			"index" => "id",
			));

		$this->addColumn("blog_id", array(
			"header" => Mage::helper("blogmate")->__("Blog Id"),
			"index"  => "blog_id",
			"width"  => "100px",
			));

		$this->addColumn("user_name", array(
			"header" => Mage::helper("blogmate")->__("User Name"),
			"index"  => "user_name",
			));

		$this->addColumn("user_email", array(
			"header" => Mage::helper("blogmate")->__("User Email"),
			"index"  => "user_email",
			));

		$this->addColumn("comment", array(
			"header" => Mage::helper("blogmate")->__("Comment"),
			"index"  => "comment",
			));

		
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
		$this->getMassactionBlock()->addItem('remove_comment', array(
			'label'=> Mage::helper('blogmate')->__('Remove Comment'),
			'url'  => $this->getUrl('*/adminhtml_comment/massRemove'),
			'confirm' => Mage::helper('blogmate')->__('Are you sure?')
			));
		return $this;
	}


}