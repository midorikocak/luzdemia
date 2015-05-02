<?php
/**
 * Magmodules.eu
 * http://www.magmodules.eu
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magmodules.eu so we can send you a copy immediately.
 *
 * @category    Magmodules
 * @package     Magmodules_Sortbydate
 * @author      Magmodules <info@magmodules.eu)
 * @copyright   Copyright (c) 2013 (http://www.magmodules.eu)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
$installer->startSetup();

	$prodEntityTypeId = $installer->getEntityTypeId('catalog_product');
	$installer->updateAttribute($prodEntityTypeId, 'created_at', 'frontend_label', 'Date');
	$installer->updateAttribute($prodEntityTypeId, 'created_at', 'used_for_sort_by', 1);

$installer->endSetup();
