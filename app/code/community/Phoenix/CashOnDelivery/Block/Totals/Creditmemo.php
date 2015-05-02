<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Phoenix
 * @package    Phoenix_CashOnDelivery
 * @copyright  Copyright (c) 2010 - 2013 PHOENIX MEDIA GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Block_Totals_Creditmemo extends Phoenix_CashOnDelivery_Block_Totals_Abstract
{
    /**
     * Set the correct parent block and the object from which we get / set our total data.
     *
     * @return Phoenix_CashOnDelivery_Block_Invoice_Totals_Cod
     */
    protected function _prepareTotals()
    {
        parent::_prepareTotals();

        $this->_parentBlock = $this->getParentBlock();
        $this->_totalObject = $this->_parentBlock->getSource();

        return $this;
    }

}