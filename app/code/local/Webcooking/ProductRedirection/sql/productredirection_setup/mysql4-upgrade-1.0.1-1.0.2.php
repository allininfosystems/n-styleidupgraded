<?php

$installer = $this;

$installer->startSetup();


$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);
$attrSetId = $installer->getDefaultAttributeSetId(Mage_Catalog_Model_Product::ENTITY);
$group = $installer->getAttributeGroup($entityTypeId, $attrSetId, 'Product Redirection');
if (!$group) {
    $installer->addAttributeGroup(Mage_Catalog_Model_Product::ENTITY, $attrSetId, 'Product Redirection');
}
$groupId = $installer->getAttributeGroupId(Mage_Catalog_Model_Product::ENTITY, $attrSetId, 'Product Redirection');


$attributeId = $installer->getAttributeId($entityTypeId, 'use_product_redirection');
$installer->addAttributeToGroup($entityTypeId, $attrSetId, $groupId, $attributeId);
$attributeId = $installer->getAttributeId($entityTypeId, 'product_redirection_url');
$installer->addAttributeToGroup($entityTypeId, $attrSetId, $groupId, $attributeId);


$attribute = $installer->getAttribute($entityTypeId, 'activate_on_disabled');
if (!$attribute)
    $installer->addAttribute('catalog_product', 'activate_on_disabled', array(
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => 'Enable redirection when product is disabled ?',
        'input' => 'boolean',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => 1,
        'required' => 0,
        'user_defined' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 0,
        'unique' => 0,
    ));

$attributeId = $installer->getAttributeId($entityTypeId, 'activate_on_disabled');
$installer->addAttributeToGroup($entityTypeId, $attrSetId, $groupId, $attributeId);





$attribute = $installer->getAttribute($entityTypeId, 'activate_on_outofstock');

if (!$attribute)
    $installer->addAttribute('catalog_product', 'activate_on_outofstock', array(
        'type' => 'int',
        'backend' => '',
        'frontend' => '',
        'label' => 'Enable redirection when product is out of stock ?',
        'input' => 'boolean',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => 1,
        'required' => 0,
        'user_defined' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 0,
        'unique' => 0,
    ));
$attributeId = $installer->getAttributeId($entityTypeId, 'activate_on_outofstock');
$installer->addAttributeToGroup($entityTypeId, $attrSetId, $groupId, $attributeId);




$installer->endSetup();

