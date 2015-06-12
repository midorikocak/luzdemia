<?php

class Dolphin_Slideshow_Model_Source_Fx
{
    public function toOptionArray()
    {
        return array(
			array('value'=>'random', 'label'=>Mage::helper('adminhtml')->__('Random')),
			array('value'=>'simpleFade', 'label'=>Mage::helper('adminhtml')->__('SimpleFade')),
			array('value'=>'curtainTopLeft', 'label'=>Mage::helper('adminhtml')->__('CurtainTopLeft')),
			array('value'=>'curtainTopRight', 'label'=>Mage::helper('adminhtml')->__('CurtainTopRight')),
			array('value'=>'curtainBottomLeft', 'label'=>Mage::helper('adminhtml')->__('CurtainBottomLeft')),
			array('value'=>'curtainBottomRight', 'label'=>Mage::helper('adminhtml')->__('CurtainBottomRight')),
			array('value'=>'curtainSliceLeft', 'label'=>Mage::helper('adminhtml')->__('CurtainSliceLeft')),
			array('value'=>'curtainSliceRight', 'label'=>Mage::helper('adminhtml')->__('CurtainSliceRight')),
			array('value'=>'blindCurtainTopLeft', 'label'=>Mage::helper('adminhtml')->__('BlindCurtainTopLeft')),
			array('value'=>'blindCurtainTopRight', 'label'=>Mage::helper('adminhtml')->__('BlindCurtainTopRight')),
			array('value'=>'blindCurtainBottomLeft', 'label'=>Mage::helper('adminhtml')->__('BlindCurtainBottomLeft')),
			array('value'=>'blindCurtainBottomRight', 'label'=>Mage::helper('adminhtml')->__('BlindCurtainBottomRight')),
			array('value'=>'blindCurtainSliceBottom', 'label'=>Mage::helper('adminhtml')->__('BlindCurtainSliceBottom')),
			array('value'=>'blindCurtainSliceTop', 'label'=>Mage::helper('adminhtml')->__('BlindCurtainSliceTop')),
			array('value'=>'stampede', 'label'=>Mage::helper('adminhtml')->__('Stampede')),
			array('value'=>'mosaic', 'label'=>Mage::helper('adminhtml')->__('Mosaic')),
			array('value'=>'mosaicReverse', 'label'=>Mage::helper('adminhtml')->__('MosaicReverse')),
			array('value'=>'mosaicRandom', 'label'=>Mage::helper('adminhtml')->__('MosaicRandom')),
			array('value'=>'mosaicSpiral', 'label'=>Mage::helper('adminhtml')->__('MosaicSpiral')),
			array('value'=>'mosaicSpiralReverse', 'label'=>Mage::helper('adminhtml')->__('MosaicSpiralReverse')),
			array('value'=>'topLeftBottomRight', 'label'=>Mage::helper('adminhtml')->__('TopLeftBottomRight')),
			array('value'=>'bottomRightTopLeft', 'label'=>Mage::helper('adminhtml')->__('BottomRightTopLeft')),
			array('value'=>'bottomLeftTopRight', 'label'=>Mage::helper('adminhtml')->__('BottomLeftTopRight')),
			array('value'=>'bottomLeftTopRight', 'label'=>Mage::helper('adminhtml')->__('BottomLeftTopRight')),
			array('value'=>'scrollLeft', 'label'=>Mage::helper('adminhtml')->__('ScrollLeft')),
			array('value'=>'scrollRight', 'label'=>Mage::helper('adminhtml')->__('ScrollRight')),
			array('value'=>'scrollHorz', 'label'=>Mage::helper('adminhtml')->__('ScrollHorz')),
			array('value'=>'scrollBottom', 'label'=>Mage::helper('adminhtml')->__('ScrollBottom')),
			array('value'=>'scrollTop', 'label'=>Mage::helper('adminhtml')->__('ScrollTop')),

        );
    }
}
