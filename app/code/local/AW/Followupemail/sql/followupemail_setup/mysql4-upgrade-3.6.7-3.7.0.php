<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Followupemail
 * @version    3.7.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


$installer = $this;

$installer->startSetup();
$this->getConnection()->dropColumn($this->getTable('followupemail/rule'), 'unsubscribed_customers');

try {
    $installer->run("
        CREATE TABLE IF NOT EXISTS {$this->getTable('followupemail/visitorsemail')} (
        `item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `session_id` varchar(64) NOT NULL,
        `quote_id` int(10) unsigned NOT NULL,
        `email` varchar(255) DEFAULT NULL,
        `quote_updated_at` datetime NOT NULL,
        PRIMARY KEY (`item_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();