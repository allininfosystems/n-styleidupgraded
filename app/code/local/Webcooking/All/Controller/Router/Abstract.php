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
 * @package    Webcooking_All
 * @copyright  Copyright (c) 2011-2013 Vincent René Lucien Enjalbert
 * @license    http://www.web-cooking.net/licences/magento/LICENSE-EN.txt
 */
abstract class Webcooking_All_Controller_Router_Abstract extends Mage_Core_Controller_Varien_Router_Standard {

   abstract protected function _getModuleAlias($request);
   abstract protected function _getModuleName($request);
   
   abstract protected function _getControllerName($request);
   abstract protected function _getActionName($request);
   abstract protected function _getRouteName($request);
   protected function _actionBeforeDispath($request) {
       return $request;
   }


   public function match(Zend_Controller_Request_Http $request) {
        if (!$this->_beforeModuleMatch()) {
            return false;
        }

        
        $path = explode('/', trim($request->getPathInfo(), '/'));

        // If path doesn't match your module requirements
        if (count($path) > 2 || $path[0] != $this->_getRouteName($request)) {
            return false;
        }
      
        $realModule = $this->_getModuleName($request);
        $controller = $this->_getControllerName($request);
        $action = $this->_getActionName($request);
        $controllerClassName = $this->_validateControllerClassName(
                        $realModule,
                        $controller
        );
        // If controller was not found
        if (!$controllerClassName) {
            return false;
        }
        // Instantiate controller class
        $controllerInstance = Mage::getControllerInstance(
                        $controllerClassName,
                        $request,
                        $this->getFront()->getResponse()
        );
        // If action is not found
        if (!$controllerInstance->hasAction($action)) {
            return false; // 
        }

        // Set request data
        $request->setRouteName($this->_getRouteName($request));
        $request->setModuleName($this->_getModuleAlias($request));
        $request->setControllerName($controller);
        $request->setActionName($action);
        $request->setControllerModule($realModule);
        $request = $this->_actionBeforeDispath($request);
        if(!$request) {
            return false;
        }
        $request->setDispatched(true);
        $controllerInstance->dispatch($action);
        // Indicate that our route was dispatched
        return true;
    }



}