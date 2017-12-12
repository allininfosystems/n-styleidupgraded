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
class Webcooking_ProductRedirection_Model_Catalog_Url extends Mage_Catalog_Model_Url {

    protected function _refreshProductRewrite(Varien_Object $product, Varien_Object $category) {
        if (version_compare(Mage::getVersion(), '1.7.0.0') < 0) {
            return $this->_refreshProductRewrite14($product, $category);
        }
        return $this->_refreshProductRewrite17($product, $category);
    }

    protected function _refreshProductRewrite14(Varien_Object $product, Varien_Object $category) {
        if ($category->getId() == $category->getPath()) {
            return $this;
        }
        if ($product->getUrlKey() == '') {
            $urlKey = $this->getProductModel()->formatUrlKey($product->getName());
        } else {
            $urlKey = $this->getProductModel()->formatUrlKey($product->getUrlKey());
        }

        $idPath = $this->generatePath('id', $product, $category);
        $targetPath = $this->generatePath('target', $product, $category);
        $requestPath = $this->getProductRequestPath($product, $category);

        $categoryId = null;
        $updateKeys = true;
        if ($category->getLevel() > 1) {
            $categoryId = $category->getId();
            $updateKeys = false;
        }

        $options = '';
        $useProductRedirection = Mage::getResourceModel('catalog/product')->getAttributeRawValue($product->getEntityId(), 'use_product_redirection', $category->getStoreId());
        if ($useProductRedirection) {
            $productRedirectionUrl = Mage::getResourceModel('catalog/product')->getAttributeRawValue($product->getEntityId(), 'product_redirection_url', $category->getStoreId());
            if ($productRedirectionUrl) {
                $targetPath = $productRedirectionUrl;
                $options = 'RP';
            }
        }
        
        $rewriteData = array(
            'store_id' => $category->getStoreId(),
            //'category_id' => $categoryId,
            'product_id' => $product->getId(),
            'id_path' => $idPath,
            'request_path' => $requestPath,
            'target_path' => $targetPath,
            'is_system' => 1,
            'options' => $options
        );

        if($categoryId) {
            $rewriteData['category_id'] = $categoryId;
        }
        //$this->getResource()->saveRewrite($rewriteData, $this->_rewrite);
        $resource = $this->getResource();
        $adapter = Mage::getSingleton('core/resource')->getConnection('core_write');
        try {
            $values = array_values($rewriteData);
            $values = array_map(array($adapter, 'quote'), $values);
            $sql = "INSERT IGNORE INTO "
                    . $adapter->quoteIdentifier($resource->getMainTable(), true)
                    . ' (' . implode(', ', array_keys($rewriteData)) . ') '
                    . 'VALUES (' . implode(', ', $values).')';
            $sql .= " ON DUPLICATE KEY UPDATE target_path = ".$adapter->quote($rewriteData['target_path']).", is_system = ".$adapter->quote($rewriteData['is_system']).", options = ".$adapter->quote($rewriteData['options'])."";
           
            $adapter->query($sql);
            //$result = $adapter->insertOnDuplicate($resource->getMainTable(), $rewriteData);
        } catch (Exception $e) {
            //return $this;
            //Mage::logException($e);
            //Mage::throwException(Mage::helper('catalog')->__('An error occurred while saving the URL rewrite'));
        }

        if ($this->_rewrite && $this->_rewrite->getId()) {
            if ($rewriteData['request_path'] != $this->_rewrite->getRequestPath()) {
                // Update existing rewrites history and avoid chain redirects
                $where = array('target_path = ?' => $this->_rewrite->getRequestPath());
                if ($this->_rewrite->getStoreId()) {
                    $where['store_id = ?'] = (int) $this->_rewrite->getStoreId();
                }
                $adapter->update(
                        $resource->getMainTable(), array('target_path' => $rewriteData['request_path']), $where
                );
            }
        }





        if ($this->getShouldSaveRewritesHistory($category->getStoreId())) {
            $this->_saveRewriteHistory($rewriteData, $this->_rewrite);
        }

        if ($updateKeys && $product->getUrlKey() != $urlKey) {
            $product->setUrlKey($urlKey);
            $this->getResource()->saveProductAttribute($product, 'url_key');
        }
        if ($updateKeys && $product->getUrlPath() != $requestPath) {
            $product->setUrlPath($requestPath);
            $this->getResource()->saveProductAttribute($product, 'url_path');
        }

     
        return $this;
    }

    protected function _refreshProductRewrite17(Varien_Object $product, Varien_Object $category) {
        if ($category->getId() == $category->getPath()) {
            return $this;
        }
        if ($product->getUrlKey() == '') {
            $urlKey = $this->getProductModel()->formatUrlKey($product->getName());
        } else {
            $urlKey = $this->getProductModel()->formatUrlKey($product->getUrlKey());
        }

        $idPath = $this->generatePath('id', $product, $category);
        $targetPath = $this->generatePath('target', $product, $category);
        $requestPath = $this->getProductRequestPath($product, $category);

        $categoryId = null;
        $updateKeys = true;
        if ($category->getLevel() > 1) {
            $categoryId = $category->getId();
            $updateKeys = false;
        }

        $options = '';
        $useProductRedirection = Mage::getResourceModel('catalog/product')->getAttributeRawValue($product->getEntityId(), 'use_product_redirection');
        if ($useProductRedirection) {
            $productRedirectionUrl = Mage::getResourceModel('catalog/product')->getAttributeRawValue($product->getEntityId(), 'product_redirection_url');
            if ($productRedirectionUrl) {
                $targetPath = $productRedirectionUrl;
                $options = 'RP';
            }
        }

        $rewriteData = array(
            'store_id' => $category->getStoreId(),
            'category_id' => $categoryId,
            'product_id' => $product->getId(),
            'id_path' => $idPath,
            'request_path' => $requestPath,
            'target_path' => $targetPath,
            'is_system' => 1,
            'options' => $options
        );

        $this->getResource()->saveRewrite($rewriteData, $this->_rewrite);

        if ($this->getShouldSaveRewritesHistory($category->getStoreId())) {
            $this->_saveRewriteHistory($rewriteData, $this->_rewrite);
        }

        if ($updateKeys && $product->getUrlKey() != $urlKey) {
            $product->setUrlKey($urlKey);
            $this->getResource()->saveProductAttribute($product, 'url_key');
        }
        if ($updateKeys && $product->getUrlPath() != $requestPath) {
            $product->setUrlPath($requestPath);
            $this->getResource()->saveProductAttribute($product, 'url_path');
        }

        return $this;
    }

}