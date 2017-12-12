<?php

$installer = $this;

$installer->startSetup();


$entityTypeId = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);
$attrSetIds = $installer->getAllAttributeSetIds($entityTypeId);
foreach ($attrSetIds as $attrSetId) {
    $group = $installer->getAttributeGroup($entityTypeId, $attrSetId, 'Product Redirection');
    if (!$group) {
        $installer->addAttributeGroup(Mage_Catalog_Model_Product::ENTITY, $attrSetId, 'Product Redirection');
    }
    $groupId = $installer->getAttributeGroupId(Mage_Catalog_Model_Product::ENTITY, $attrSetId, 'Product Redirection');


    $attributeId = $installer->getAttributeId($entityTypeId, 'use_product_redirection');
    $installer->addAttributeToGroup($entityTypeId, $attrSetId, $groupId, $attributeId);
    $attributeId = $installer->getAttributeId($entityTypeId, 'product_redirection_url');
    $installer->addAttributeToGroup($entityTypeId, $attrSetId, $groupId, $attributeId);
    $attributeId = $installer->getAttributeId($entityTypeId, 'activate_on_disabled');
    $installer->addAttributeToGroup($entityTypeId, $attrSetId, $groupId, $attributeId);
    $attributeId = $installer->getAttributeId($entityTypeId, 'activate_on_outofstock');
    $installer->addAttributeToGroup($entityTypeId, $attrSetId, $groupId, $attributeId);
}


$installer->endSetup();

