<?php
class Magik_BlogMate_Block_Adminhtml_Blog_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{

		

		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset("blogmate_form", array("legend"=>Mage::helper("blogmate")->__("Blog post")));


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
			"name"     => "short_description",
			"style"    => "width:857px;",
			));

		try{
			$config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
			$config->setData(Mage::helper('blogmate')->recursiveReplace('/blogmate/','/'.(string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/',
				$config->getData()
				)
			);

		} catch (Exception $ex){
			$config = null;
		}

		$fieldset->addField('blog_content', 'editor', array(
		    'name'      => 'blog_content',
		    'label'     => Mage::helper('blogmate')->__('Content'),
		    'title'     => Mage::helper('blogmate')->__('Content'),
		    'style' 		=> 'width:854px; height:600px;',
		    'config'    => $config,
		    'wysiwyg'   => true,
		    'required'  => true,
		    ));

		$fieldset->addField('short_blog_content', 'editor', array(
		    'name'      => 'short_blog_content',
		    'label'     => Mage::helper('blogmate')->__('Short Content'),
		    'title'     => Mage::helper('blogmate')->__('Short Content'),
		    'style' 		=> 'width:854px; height:300px;',
		    'note'			=> 'Short content to display in blog listing page',
		    'config'    => $config,
		    'wysiwyg'   => true,
		    'required'  => true,
		    ));

		$category_select = Mage::getModel('blogmate/blogcategorylist')->toOptionArray();
		$fieldset->addField('categories_selected', 'select', array(
			'name'     => 'categories_selected[]',
			'label'    => Mage::helper('blogmate')->__('Category'),
			'title'    => Mage::helper('blogmate')->__('Category'), 
			'required' => true,
			
			'values'   => $category_select,
			));

		$fieldset->addField("tags", "text", array(
			"label"    => Mage::helper("blogmate")->__("Tags"),					
			"class"    => "required-entry",
			"style"    => "width:857px;",
			"required" => true,
			"name"     => "tags",
			));
		
		$fieldset->addField("display_order", "text", array(
			"label"    => Mage::helper("blogmate")->__("Display Order"),
			"style"    => "width:857px;",
			"name"     => "display_order",
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

		
		$fieldset->addField("enable_comment", "select", array(
			"label" => Mage::helper("blogmate")->__("Enable Comment"),
			"name" => "enable_comment",
			"value"=>2,
			"values"  => array(array('value' => 2,'label' => Mage::helper('blogmate')->__('Disabled'),),
					     array('value' => 1,'label' => Mage::helper('blogmate')->__('Enabled'),),
					    ),
			"class" => "required-entry",
			"required" => true,
		)); 

		$fieldset->addField("status", "select", array(
			"label" => Mage::helper("blogmate")->__("Status"),
			"name" => "status",
			"value"=>2,
			"values"    => array(array('value' => 2,'label' => Mage::helper('blogmate')->__('Disabled'),),
					     array('value' => 1,'label'     => Mage::helper('blogmate')->__('Enabled'),),
						      ),
			"class"    => "required-entry",
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

		$eventElem->setAfterElementHtml("
				<script type='text/javascript'>       
					function set_title_slug(text) {
					    var title_slug_value = text.toLowerCase().replace(/[^a-zA-Z0-9]{2,}/g,' ').replace(/\W/g,'-').replace(/\A/g, '').replace(/-$/, '').replace(/^-/, '');
					    document.getElementById('title_slug').value = title_slug_value;
					}
		    </script>"
		);

		if (Mage::getSingleton("adminhtml/session")->getBlogData())
		{
			$form->setValues(Mage::getSingleton("adminhtml/session")->getBlogData());
			Mage::getSingleton("adminhtml/session")->setBlogData(null);
		} 
		elseif(Mage::registry("blog_data")) {
			$form->setValues(Mage::registry("blog_data")->getData());
		}
		return parent::_prepareForm();
	}
}
