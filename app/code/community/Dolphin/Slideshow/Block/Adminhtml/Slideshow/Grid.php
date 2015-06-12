    <?php
     
    class Dolphin_Slideshow_Block_Adminhtml_Slideshow_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {
        public function __construct()
        {
            parent::__construct();
            $this->setId('slideshowGrid');
            // This is the primary key of the database
            $this->setDefaultSort('slideshow_id');
            $this->setDefaultDir('ASC');
            $this->setSaveParametersInSession(true);
            $this->setUseAjax(true);
        }
     
        protected function _prepareCollection()
        {
            $collection = Mage::getModel('slideshow/slideshow')->getCollection();
			foreach($collection as $link){
				if($link->getStores() && $link->getStores() != 0 ){
					$link->setStores(explode(',',$link->getStores()));
				}
				else{
					$link->setStores(array('0'));
				}
			}
            $this->setCollection($collection);
            return parent::_prepareCollection();
        }
     
        protected function _prepareColumns()
        {
            $this->addColumn('slideshow_id', array(
                'header'    => Mage::helper('slideshow')->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'slideshow_id',
            ));
			
             $this->addColumn('filename', array(
				'header' => Mage::helper('slideshow')->__('Slide Image'),
				'align' => 'left',
				'index' => 'filename',
				'renderer' => 'slideshow/adminhtml_grid_renderer_image',
				'width'	=> '130px',
				'align'	=> 'center',
				'escape'    => true,
				'sortable'  => false,
				'filter'    => false,
			)); 
            $this->addColumn('title', array(
                'header'    => Mage::helper('slideshow')->__('Title'),
                'align'     =>'left',
                'index'     => 'title',
            ));
            
			if (!Mage::app()->isSingleStoreMode()) {
				$this->addColumn('stores', array(
				 'header'        => Mage::helper('slideshow')->__('Store'),
				 'index'         => 'stores',
				 'type'          => 'store',
				 'store_all'     => true,
				 'store_view'    => true,
				 'sortable'      => false,
				 'filter_condition_callback'
					 => array($this, '_filterStoreCondition'),
				));
		   }
		   
		   /*if (!Mage::app()->isSingleStoreMode()) {
				$this->addColumn('stores', array(
					'header'        => Mage::helper('slideshow')->__('Store'),
					'index'     => 'stores',
					'type'      => 'store',
					'store_view'=> true,
				));
			}*/
			
            $this->addColumn('sort_order', array(
            		'header'    => Mage::helper('slideshow')->__('Sort Order'),
            		'align'     =>'left',
            		'index'     => 'sort_order',
            ));
			
            $this->addColumn('status', array(
     
                'header'    => Mage::helper('slideshow')->__('Status'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'status',
                'type'      => 'options',
                'options'   => array(
                    1 => 'Active',
                    0 => 'Inactive',
                ),
            ));
     
            return parent::_prepareColumns();
        }
     
        public function getRowUrl($row)
        {
            return $this->getUrl('*/*/edit', array('id' => $row->getId()));
        }
     
        public function getGridUrl()
        {
          return $this->getUrl('*/*/grid', array('_current'=>true));
        }
		
		protected function _filterStoreCondition($collection, $column)
		{
			
			if (!$value = $column->getFilter()->getValue()) {
				return;
			}
			$this->getCollection()->addStoreFilter($value);
		}
     
    }