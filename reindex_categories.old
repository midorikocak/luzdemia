<?php
require_once('app/Mage.php');
Mage::app('default'); 

$categories = Mage::getModel('catalog/category')->getCollection()
    ->addAttributeToSelect('*');

foreach ($categories as $category) {

    $process = Mage::getModel('index/process')->load(5); $process->reindexAll();      
    $process = Mage::getModel('index/process')->load(6); $process->reindexAll(); 

    $cat = Mage::getModel("catalog/category")->load($category->getId());
    var_dump($cat->getData('wide_images')); // Gives result
    var_dump($cat->getWideImages()); // Gives result

    var_dump($cat->getName()); // My Cool Category Name

}