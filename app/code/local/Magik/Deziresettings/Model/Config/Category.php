<?php


class Magik_Deziresettings_Model_Config_Category
{
  public function toOptionArray()
   {
    $collection = Mage::getModel('catalog/category')
                  ->getCollection()
                  ->addAttributeToSelect('*')
                  ->addIsActiveFilter()
                  ->addAttributeToFilter('level',2)
                  ->addOrderField('name');

    $categories = array();
     
    foreach ($collection as $cat) {
      if($cat->getName() != ''){
      $categories[] = ( array(
          'label' => (string) $cat->getName(),
          'value' => $cat->getId()
              ));
      }
    }
    return $categories;

  }

}
