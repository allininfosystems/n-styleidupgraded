<?php
		ini_set('display_startup_errors',1);
		ini_set('display_errors',1);
		error_reporting(-1);
		
      //increase execution time
      ini_set('max_execution_time', 900); //900 seconds = 15 minutes
 
      //require Magento
      require_once 'app/Mage.php';
      $app = Mage::app('admin');
      umask(0);
 
      //enable Error Reporting
      //error_reporting(E_ALL & ~E_NOTICE);
 
      try {
            //CLEAN OVERALL CACHE
            flush();
            Mage::app()->cleanCache();
            // CLEAN IMAGE CACHE
            flush();
            Mage::getModel('catalog/product_image')->clearCache();
            //print
            print 'done';
      }
      catch(Exception $e) {
            //something went wrong...
            print($e->getMessage());
      }
?>