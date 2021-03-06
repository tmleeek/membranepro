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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright  Copyright (c) Daniel Ifrim
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adds most used order address fields on romanian stores.
 *
 * @category   PfpjRom
 * @package    PfpjRom_CustomerRom
 * @author     Daniel Ifrim
 */

$installer = $this;
/* @var $installer PfpjRom_SalesRom_Model_Entity_Setup */
$installer->startSetup();

$order_address_entity_type_id = $installer->getEntityTypeId('order_address');

$installer->addAttribute('order_address', 'pfpj_tip_pers', array(
    'type' => 'int', // same as default
    'input' => 'radios',
    'backend' => '', // same as default
    'frontend' => 'customerrom/entity_address_attribute_frontend_tippers',
    'backend' => 'customerrom/entity_address_attribute_backend_tippers',
    'source' => 'customerrom/entity_address_attribute_source_tippers',
    'input_renderer' => 'customerrom/address_renderer_tippers',
    'label'    => 'Person type',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'    => (!$installer->getTippersConfig()->isFieldDisabled('pfpj_tip_pers')),
    'required' => 0,
    'user_defined' => true,
    'visible_on_front' => false, // same as default
    'used_for_price_rules' => true, // same as default
    'position' => 0,
    'sort_order' => 25,
));
$tip_pers_attribute = $installer->getAttribute($order_address_entity_type_id, 'pfpj_tip_pers');

//$intOptionId = $installer->addAttributeOption(array(
$installer->addAttributeOption(array(
	'attribute_id' => $tip_pers_attribute['attribute_id'],
	'value' => array(0 => array(0 => Mage::getModel('customerrom/entity_address_attribute_source_tippers')->getNaturalPersonValue(), 1 => Mage::getModel('customerrom/entity_address_attribute_source_tippers')->getNaturalPersonValue())),
	'order' => array(0 => 10),
	'delete' => array(0 => ''),
));

//$installer->updateAttribute('order_address', 'pfpj_tip_pers', 'default_value', $intOptionId);
$installer->updateAttribute('order_address', 'pfpj_tip_pers', 'default_value', Mage::getModel('customerrom/entity_address_attribute_source_tippers')->getNaturalPersonValue());

$installer->addAttributeOption(array(
	'attribute_id' => $tip_pers_attribute['attribute_id'],
	'value' => array(0 => array(0 => Mage::getModel('customerrom/entity_address_attribute_source_tippers')->getLegalEntityValue(), 1 => Mage::getModel('customerrom/entity_address_attribute_source_tippers')->getLegalEntityValue())),
	'order' => array(0 => 20),
	'delete' => array(0 => ''),
));

$installer->addAttribute('order_address', 'pfpj_cui', array(
    'type' => 'varchar', // same as default
    'input' => 'text', // same as default
    'label'    => 'Tax Registration Number',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'    => (!$installer->getTippersConfig()->isFieldDisabled('pfpj_cui')),
    'required' => 0,
    'user_defined' => true,
    'visible_on_front' => false, // same as default
    'used_for_price_rules' => true, // same as default
    'position' => 0,
    'sort_order' => 26,
));

$installer->addAttribute('order_address', 'pfpj_reg_com', array(
    'type' => 'varchar', // same as default
    'input' => 'text', // same as default
    'label'    => 'Number in Trade Register Office',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'    => (!$installer->getTippersConfig()->isFieldDisabled('pfpj_reg_com')),
    'required' => 0,
    'user_defined' => true,
    'visible_on_front' => false, // same as default
    'used_for_price_rules' => true, // same as default
    'position' => 0,
    'sort_order' => 27,
));

$installer->addAttribute('order_address', 'pfpj_banca', array(
    'type' => 'varchar', // same as default
    'input' => 'text', // same as default
    'label'    => 'Bank',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'    => (!$installer->getTippersConfig()->isFieldDisabled('pfpj_banca')),
    'required' => 0,
    'user_defined' => true,
    'visible_on_front' => false, // same as default
    'used_for_price_rules' => true, // same as default
    'position' => 0,
    'sort_order' => 28,
));

$installer->addAttribute('order_address', 'pfpj_iban', array(
    'type' => 'varchar', // same as default
    'input' => 'text', // same as default
    'label'    => 'IBAN',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'    => (!$installer->getTippersConfig()->isFieldDisabled('pfpj_iban')),
    'required' => 0,
    'user_defined' => true,
    'visible_on_front' => false, // same as default
    'used_for_price_rules' => true, // same as default
    'position' => 0,
    'sort_order' => 29,
));

// isn't used at this time.
$installer->addAttribute('order_address', 'pfpj_cnp', array(
    'type' => 'varchar', // same as default
    'input' => 'text', // same as default
    'label'    => 'Personal Identification Code',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'    => (!$installer->getTippersConfig()->isFieldDisabled('pfpj_cnp')),
    'required' => 0,
    'user_defined' => true,
    'visible_on_front' => false, // same as default
    'used_for_price_rules' => true, // same as default
    'position' => 0,
    'sort_order' => 30,
));

$installer->addAttribute('order_address', 'pfpj_serienr_buletin', array(
    'type' => 'varchar', // same as default
    'input' => 'text', // same as default
    'label'    => 'ID Paper Serie/Number',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'    => (!$installer->getTippersConfig()->isFieldDisabled('pfpj_serienr_buletin')),
    'required' => 0,
    'user_defined' => true,
    'visible_on_front' => false, // same as default
    'used_for_price_rules' => true, // same as default
    'position' => 0,
    'sort_order' => 31,
));

foreach ($installer->getTippersConfig()->getFields() as $fieldName => $fieldConfig) {
	$installer->getConnection()->addColumn($installer->getTable('sales_flat_quote_address'), $fieldName, 'varchar(255) NULL');
}

$installer->updateAttribute($order_address_entity_type_id, 'postcode', 'is_required', 0);

$installer->endSetup();