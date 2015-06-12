    <?php
     
    class Dolphin_Slideshow_Block_Adminhtml_Slideshow_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $form = new Varien_Data_Form();
            $this->setForm($form);
            $fieldset = $form->addFieldset('slideshow_form', array('legend'=>Mage::helper('slideshow')->__('Item information')));
           
            $fieldset->addField('title', 'text', array(
                'label'     => Mage::helper('slideshow')->__('Title'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'title',
            ));
			
			if (!Mage::app()->isSingleStoreMode()) {
				$fieldset->addField('stores', 'multiselect', array(
					'name'      => 'stores[]',
					'label'     => Mage::helper('slideshow')->__('Select Store'),
					'title'     => Mage::helper('slideshow')->__('Select Store'),
					'required'  => true,
					'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
				));
			}
			else {
				$fieldset->addField('stores', 'hidden', array(
					'name'      => 'stores[]',
					'value'     => Mage::app()->getStore(true)->getId()
				));
			}
				
            $fieldset->addField('slide_url', 'text', array(
                'label'     => Mage::helper('slideshow')->__('Url'),
                'required'  => false,
                'name'      => 'slide_url',
            ));

            $fieldset->addField('slide_target', 'select', array(
                'label'     => Mage::helper('slideshow')->__('Target'),
                'name'      => 'slide_target',
                'values'    => array(
                    array(
                        'value'     => '_blank',
                        'label'     => Mage::helper('slideshow')->__('Blank'),
                    ),
                    array(
                        'value'     => '_new',
                        'label'     => Mage::helper('slideshow')->__('New'),
                    ),
                    array(
                        'value'     => '_parent',
                        'label'     => Mage::helper('slideshow')->__('Parent'),
                    ),
                    array(
                        'value'     => '_self',
                        'label'     => Mage::helper('slideshow')->__('Self'),
                    ),
                    array(
                        'value'     => '_top',
                        'label'     => Mage::helper('slideshow')->__('Top'),
                    ),
                ),
            ));

			  $fieldset->addField('filename', 'image', array(
				  'label'     => Mage::helper('slideshow')->__('Image File'),
				  'required'  => true,
				  'name'      => 'filename',
			  ));
     
            $fieldset->addField('content', 'editor', array(
                'name'      => 'content',
                'label'     => Mage::helper('slideshow')->__('Content'),
                'title'     => Mage::helper('slideshow')->__('Content'),
                'style'     => 'width:98%; height:100px;',
                'wysiwyg'   => false,
                'required'  => false,
            ));	   
            
            $fieldset->addField('sort_order', 'text', array(
            		'label'     => Mage::helper('slideshow')->__('Sort Order'),
            		'name'      => 'sort_order',
            ));
			
            $fieldset->addField('status', 'select', array(
                'label'     => Mage::helper('slideshow')->__('Status'),
                'name'      => 'status',
                'values'    => array(
                    array(
                        'value'     => 1,
                        'label'     => Mage::helper('slideshow')->__('Active'),
                    ),
     
                    array(
                        'value'     => 0,
                        'label'     => Mage::helper('slideshow')->__('Inactive'),
                    ),
                ),
            ));

            if ( Mage::getSingleton('adminhtml/session')->getSlideshowData() )
            {
                $form->setValues(Mage::getSingleton('adminhtml/session')->getSlideshowData());
                Mage::getSingleton('adminhtml/session')->setSlideshowData(null);
            } elseif ( Mage::registry('slideshow_data') ) {
                $form->setValues(Mage::registry('slideshow_data')->getData());
            }
            return parent::_prepareForm();
        }
    }