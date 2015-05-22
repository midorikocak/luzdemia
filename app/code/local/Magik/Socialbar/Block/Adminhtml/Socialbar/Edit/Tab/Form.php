<?php
class Magik_Socialbar_Block_Adminhtml_Socialbar_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("socialbar_form", array("legend"=>Mage::helper("socialbar")->__("Item information")));

				$reqid= $this->getRequest()->getParam("id");
				$allid = Mage::getModel('socialbar/socialbar')->load($reqid);
				$countercategory=$allid['show_category'];

				$fieldset->addField('name', 'text', array(
				    'label'     => Mage::helper('socialbar')->__('Name'),
				    'class'     => 'required-entry',
				    'required'  => true,
				    'name'      => 'name',
				));


				$fieldset->addField('store_id', 'multiselect', array(
				      'name'      => 'store_id[]',
				      'label'     => 'Store View',
				      'title'     => '',
				      'required'  => true,
				      'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
				     
				));

				$socialsites_select = Mage::getModel('socialbar/socialsitelist')->toOptionArray();
				$fieldset->addField('show_socialsites', 'multiselect', array(
					    'label'     => 'Choose Service',
					    'name'      => 'show_socialsites[]',
					    'values'    => $socialsites_select,
					    'class'     => 'required-entry',
					    'required'  => true,
					      
				));


				$eventElemall=$fieldset->addField("show_pagelocation", "select", array(
				"label" => 'Apply To',
				"name" => "show_pagelocation",
				"values"    => array(
						      array(
							      'value'     => '',
							      'label'     => Mage::helper('socialbar')->__('Select Here'),
							  ),
						      array(
							      'value'     => 'chkhomelevel',
							      'label'     => Mage::helper('socialbar')->__('Home Page'),
							  ),
						      array(
								'value'     => 'chkcategorylevel',
								'label'     => Mage::helper('socialbar')->__('Category Page'),
							    ),
						      array(
								'value'     => 'chkproductlevel',
								'label'     => Mage::helper('socialbar')->__('Product Page'),
							    ),
						      array(
								'value'     => 'chkcmslevel',
								'label'     => Mage::helper('socialbar')->__('CMS Page'),
							    ),
						      array(
								'value'     => 'chkallpagelevel',
								'label'     => Mage::helper('socialbar')->__('All Page'),
							    ),
						      ),
				"onclick" => "applyforall()",
				'required'  => false,
				));
				
				$category_select = Mage::getModel('socialbar/socialcategorylist')->toOptionArray();
				  $fieldset->addField('show_category', 'multiselect', array(
					    'label'     => '',
					      'name'      => 'show_category[]',
					    'values'    => $category_select,
					    "style" => $countercategory != ''  ? 'display:block' : 'display:none' ,
					      
				    )); 

				$eventElemall->setAfterElementHtml('
				<script type="text/javascript">
				    function applyforall(){
					
					if(document.getElementById("show_pagelocation").value == "chkcategorylevel"){
					document.getElementById("show_category").style.display="block";
					
					}else{ document.getElementById("show_category").style.display="none"; }
				    }
				</script>');

				if (Mage::getSingleton("adminhtml/session")->getSocialbarData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getSocialbarData());
					Mage::getSingleton("adminhtml/session")->setSocialbarData(null);
				} 
				elseif(Mage::registry("socialbar_data")) {
				    $form->setValues(Mage::registry("socialbar_data")->getData());
				}
				return parent::_prepareForm();
		}
}
