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
require_once 'Mage/Adminhtml/controllers/Catalog/ProductController.php';

class Webcooking_ProductRedirection_Adminhtml_Catalog_Product_Edit_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    protected function _construct()
    {
        $this->setUsedModuleName('Webcooking_ProductRedirection');
    }


    public function indexAction()
    {
        return $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('productredirection/adminhtml_catalog_product_edit_product_grid',
                    'productredirection.adminhtml.catalog.product.edit.product.grid')
                ->setFirstShow(true)
                ->toHtml()
           );
    }
    public function gridAction()
    {
        return $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('productredirection/adminhtml_catalog_product_edit_product_grid',
                    'productredirection.adminhtml.catalog.product.edit.product.grid')
                ->toHtml()
           );
    }

}
