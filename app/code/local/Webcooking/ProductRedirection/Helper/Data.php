<?php

/**
 * Vincent Enjalbert
 *
 * Version Française :
 * *****************************************************************************
 *
 * Notification de la Licence
 *
 * Ce fichier source est sujet au CLUF
 * qui est fourni avec ce module dans le fichier LICENSE-FR.txt.
 * Il est également disponible sur le web à l'adresse suivante:
 * http://www.web-cooking.net/licences/magento/LICENSE-FR.txt
 *
 * =============================================================================
 *        NOTIFICATION SUR L'UTILISATION DE L'EDITION MAGENTO
 * =============================================================================
 * Ce module est conçu pour l'édition COMMUNITY de Magento
 * WebCooking ne garantit pas le fonctionnement correct de cette extension
 * sur une autre édition de Magento excepté l'édition COMMUNITY de Magento.
 * WebCooking ne fournit pas de support d'extension en cas
 * d'utilisation incorrecte de l'édition.
 * =============================================================================
 *
 * English Version :
 * *****************************************************************************
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-EN.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.web-cooking.net/licences/magento/LICENSE-EN.txt
 *
 * =============================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =============================================================================
 * This package designed for Magento COMMUNITY edition
 * WebCooking does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * WebCooking does not provide extension support in case of
 * incorrect edition usage.
 * =============================================================================
 *
 * @category   Webcooking
 * @package    Webcooking_ProductRedirection
 * @copyright  Copyright (c) 2011-2013 Vincent René Lucien Enjalbert
 * @license    http://www.web-cooking.net/licences/magento/LICENSE-EN.txt
 */
class Webcooking_ProductRedirection_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getCategoriesWithParents($key = false) {

        $mageCacheKey = 'productredirection_category_list';
        $cacheTags = array(Mage_Catalog_Model_Category::CACHE_TAG, 'productredirection');
        $data = unserialize(Mage::app()->loadCache($mageCacheKey));
        if (!$data) {
            $data = array('name'=>array(), 'url'=>array(), 'id'=>array());


            $categories = Mage::getModel('catalog/category')
                    ->getCollection()
                    ->addAttributeToSelect('name')
                    ->addAttributeToFilter('entity_id', array('neq' => 1))
                    ->addAttributeToSort('path', 'ASC')
                    ->addAttributeToSort('name', 'ASC');
            foreach ($categories as $category) {
                $parent = $category->getParentId();
                while ($parent > 1) {
                    $parentCategory = Mage::getModel('catalog/category')->load($parent);
                    $category->setName($parentCategory->getName() . " > " . $category->getName());
                    $category->setUrl($parentCategory->getUrl() . " > " . $category->getUrl());
                    $category->setIds($parentCategory->getId() . " > " . $category->getIds()?$category->getIds():$category->getId());
                    $parent = $parentCategory->getParentId();
                }
            }

            foreach ($categories as $_category) {
                $data['name'][$_category->getId()] = $_category->getName();
                $data['url'][$_category->getId()] = $_category->getUrl();
                $data['id'][$_category->getId()]  = $_category->getIds();
            }
            Mage::app()->saveCache(serialize($data), $mageCacheKey, $cacheTags);

            unset($categories);
        }
        
        if($key && isset($data[$key]))
            return $data[$key];
        return $data;
    }
    
       
}