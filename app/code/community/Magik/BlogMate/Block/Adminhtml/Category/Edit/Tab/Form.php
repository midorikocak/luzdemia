<?php
class Magik_BlogMate_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{

		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset("blogmate_form", array("legend"=>Mage::helper("blogmate")->__("Blog Category")));

		
		$eventElem = $fieldset->addField("title", "text", array(
			"label"    => Mage::helper("blogmate")->__("Title"),					
			"class"    => "required-entry",
			"style"    => "width:857px;",
			"required" => true,
			"name"     => "title",
			"onchange" => "set_title_slug(document.getElementById('title').value)",
			));

		$fieldset->addField("title_slug", "text", array(
			"label"    => Mage::helper("blogmate")->__("Title Identifier (slug)"),					
			"class"    => "required-entry",
			"style"    => "width:857px;",
			"required" => true,
			"name"     => "title_slug",
			));

		$fieldset->addField("short_description", "text", array(
			"label"    => Mage::helper("blogmate")->__("Short Description"),
			"style"    => "width:857px;",
			"name"     => "short_description",
			));

		$fieldset->addField('meta_keywords', 'text', array(
			'name'  => 'meta_keywords',
			"style" => "width:857px;",
			'label' => Mage::helper('blogmate')->__('Meta Keywords'),
			'title' => Mage::helper('blogmate')->__('Meta Keywords')
			));

		$fieldset->addField('meta_description', 'textarea', array(
			'name'  => 'meta_description',
			"style" => "width:857px;height:90px;",
			'label' => Mage::helper('blogmate')->__('Meta Description'),
			'title' => Mage::helper('blogmate')->__('Meta Description')
			));

		$fieldset->addField("display_order", "text", array(
			"label" => Mage::helper("blogmate")->__("Display Order"),
			"style" => "width:857px;",
			"name"  => "display_order",
			));
		

		$fieldset->addField("status", "select", array(
			"label" => Mage::helper("blogmate")->__("Status"),
			"name" => "status",
			"value" => 2,
			"values" => array( array('value' => 2,'label'=> Mage::helper('blogmate')->__('Disabled'),),
				           array('value' => 1,'label'=> Mage::helper('blogmate')->__('Enabled'),),
					 ),
			"class"  => "required-entry",
			"required" => true,
		)); 

		$fieldset->addField('stores_selected', 'multiselect', array(
			'name'      => 'stores_selected[]',
			'label'     => Mage::helper('blogmate')->__('Store View'),
			'title'     => Mage::helper('blogmate')->__('Store View'),
			'required'  => true,
			"style" 		=> "width:864px;",
			'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
		));

		$category_select = Mage::getModel('blogmate/categorylist')->toOptionArray();
		$blogcategory = $fieldset->addField('cat_pid', 'select', array(
				'label'     => 'Category',
				'name'      => 'cat_pid',
				'values'    => $category_select,
				'onchange' => 'getsubcategory(this)',
		)); 
	    $editId = $this->getRequest()->getParam('id');
		if($editId !=''){
		    $editsubcategory = $stateCollection = Mage::getModel('blogmate/category')->load($editId);
		    $editsubId=$editsubcategory->getSubcategory();
		    $subcatCollection =Mage::getModel('blogmate/category')
				       ->getCollection()
			               ->addFieldToFilter('id',array('eq'=>$editsubId))
			               ->load();

		    $subcategorylist = "";
		    foreach ($subcatCollection as $_subcategorylist) {
			$subcategorylist[]= array('value'=>$_subcategorylist->getId(),'label'=>$_subcategorylist->getTitle());
		    }
		$fieldset->addField('subcategory', 'select', array(
			'label' => Mage::helper('blogmate')->__('Subcategory'),
			'required' => false,
			'name' => 'subcategory',
			'selected' => 'selected',
			'values' => $subcategorylist,
		));
		}else{

		 $fieldset->addField('subcategory', 'select', array(
			'name'  => 'subcategory',
			'label' => 'Subcategory',
			'values' => Mage::getModel('blogmate/category')->getsubcategory('0'),
		));
		}



/*
         * Add Ajax to the Country select box html output
        
*/
$blogcategory->setAfterElementHtml("<script type=\"text/javascript\">
            function getsubcategory(selectElement){
                var reloadurl = '". $this->getUrl('blogmate/adminhtml_category/subcategory') . "cat_pid/' + selectElement.value;
                new Ajax.Request(reloadurl, {
                    method: 'get',
                    onLoading: function (subcategoryform) {
                        $('subcategory').update('Searching...');
                    },
                    onComplete: function(subcategoryform) {
                        $('subcategory').update(subcategoryform.responseText);
                    }
                });
            }
        </script>"); 

        


		$eventElem->setAfterElementHtml("
				<script type='text/javascript'>       
					function set_title_slug(text) {
					    var title_slug_value = text.toLowerCase().replace(/[^a-zA-Z0-9]{2,}/g,' ').replace(/\W/g,'-').replace(/\A/g, '').replace(/-$/, '').replace(/^-/, '');
					    document.getElementById('title_slug').value = title_slug_value;
					}
		    </script>"
		);

		if (Mage::getSingleton("adminhtml/session")->getCategoryData())
		{
			$form->setValues(Mage::getSingleton("adminhtml/session")->getCategoryData());
			Mage::getSingleton("adminhtml/session")->setCategoryData(null);
		} 
		elseif(Mage::registry("category_data")) {
			$form->setValues(Mage::registry("category_data")->getData());
		}
		return parent::_prepareForm();
	}
}
