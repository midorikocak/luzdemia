<?php

class ES_Newssubscribers_Model_Subscriber extends Mage_Newsletter_Model_Subscriber
{

    public function getCouponCode()
    {
        if (!Mage::getStoreConfig('newsletter/coupon/isactive'))
            return '';

        $model = Mage::getModel('salesrule/rule');
        $model->load(Mage::getStoreConfig('newsletter/coupon/roleid'));
        $massGenerator = $model->getCouponMassGenerator();
        $massGenerator->setData(array(
            'rule_id' => Mage::getStoreConfig('newsletter/coupon/roleid'),
            'qty' => 1,
            'length' => Mage::getStoreConfig('newsletter/coupon/length'),
            'format' => Mage::getStoreConfig('newsletter/coupon/format'),
            'prefix' => Mage::getStoreConfig('newsletter/coupon/prefix'),
            'suffix' => Mage::getStoreConfig('newsletter/coupon/suffix'),
            'dash' => Mage::getStoreConfig('newsletter/coupon/dash'),
            'uses_per_coupon' => 1,
            'uses_per_customer' => 1
        ));
        $massGenerator->generatePool();
        $generated = $massGenerator->getGeneratedCount();
        $latestCuopon = max($model->getCoupons());
        $couponData = $latestCuopon->getData();
        if ($generated != 1)
            Mage::throwException($this->__('There was a problem with coupon generation.'));

        return $couponData['code'];
    }
}