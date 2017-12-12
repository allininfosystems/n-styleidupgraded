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
class Webcooking_ProductRedirection_Block_Adminhtml_Catalog_Product_Edit_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setId('productredirection_product_grid');
        $this->setRowClickCallback('setProductRedirectionUrlFromGrid');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);
    }

    protected function _beforeToHtml()
    {
        $this->setId($this->getId().'_'.$this->getIndex());
        $this->getChild('reset_filter_button')->setData('onclick', $this->getJsObjectName().'.resetFilter()');
        $this->getChild('search_button')->setData('onclick', $this->getJsObjectName().'.doFilter()');

        return parent::_beforeToHtml();
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->setStore($this->getStore())
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('url_key')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('attribute_set_id')
            ->addStoreFilter();

        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);
        
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        
   
        $this->addColumn('id', array(
            'header'    => Mage::helper('sales')->__('ID'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'entity_id'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('sales')->__('Product Name'),
            'index'     => 'name',
            'column_css_class'=> 'name'
        ));

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('catalog')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));

        $this->addColumn('sku', array(
            'header'    => Mage::helper('sales')->__('SKU'),
            'width'     => '80px',
            'index'     => 'sku',
            'column_css_class'=> 'sku'
        ));
        $this->addColumn('price', array(
            'header'    => Mage::helper('sales')->__('Price'),
            'align'     => 'center',
            'type'      => 'currency',
            'currency_code' => $this->getStore()->getCurrentCurrencyCode(),
            'rate'      => $this->getStore()->getBaseCurrency()->getRate($this->getStore()->getCurrentCurrencyCode()),
            'index'     => 'price'
        ));
        $this->addColumn('url_key', array(
            'header'    => Mage::helper('sales')->__('Url Key'),
            'align'     => 'center',
            'type'      => 'text',
            'index'     => 'url_key',
            'column_css_class'     => 'pr_url_key',
            'frame_callback' => array($this, 'decorateUrl'),
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('productredirection/adminhtml_catalog_product_edit_product/grid');
    }


    public function getStore()
    {
        return Mage::app()->getStore();
    }
    
    public function decorateUrl($value, $row, $column, $isExport) {
        $value = trim($value);
        $suffix =  Mage::getStoreConfig('catalog/seo/product_url_suffix');
        if(!preg_match('%'.$suffix.'$%i', $value)) {
            $value .= $suffix;
        }
        return $value;
    }
       
}