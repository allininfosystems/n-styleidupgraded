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
class Webcooking_ProductRedirection_Model_Observer {

    public function _manageActivateOnOutofStockEvent($stockItem) {
        $product = Mage::getModel('catalog/product')->load($stockItem->getProductId(), array('use_product_redirection', 'product_redirection_url', 'activate_on_outofstock'));
        if(!$product->getData('use_product_redirection') && $product->getData('product_redirection_url') && $product->getData('activate_on_outofstock') &&  $stockItem->getOrigData('is_in_stock') != $stockItem->getData('is_in_stock') && $stockItem->getData('is_in_stock') == 0) {
            //if product dont use redirection yet, and has a redirection url, and has activate on outofstock on, and has just passed to out_of_stock
          $product->setData('use_product_redirection', 1);
          $product->save();
        }
    }
    
    public function observeCataloginventoryStockItemSaveAfter($observer) {
        $stockItem = $observer->getEvent()->getItem();
        $this->_manageActivateOnOutofStockEvent($stockItem);
    }
    
    public function _manageActivateOnDisableEvent($product) {
        if(!$product->getData('use_product_redirection') && $product->getData('product_redirection_url') && $product->getData('activate_on_disabled') &&  $product->getOrigData('status') != $product->getData('status') && $product->getData('status') == Mage_Catalog_Model_Product_Status::STATUS_DISABLED) {
            //if product dont use redirection yet, and has a redirection url, and has activate on disable on, and has just passed to disabled
          $product->setData('use_product_redirection', 1);
          $product->save();
        }
    }
    
    public function observeCatalogProductSaveAfter($observer) {
        $product = $observer->getEvent()->getProduct();
        $this->_manageActivateOnDisableEvent($product);
    }
}