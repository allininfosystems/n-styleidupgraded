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


class AW_Followupemail_Model_Mysql4_Visitorsemail extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('followupemail/visitorsemail', 'item_id');
    }

    public function getVisitorBySessionId($sessionId)
    {
        $db = $this->_getReadAdapter();

        $select = $db->select()
            ->from($this->getMainTable(), 'item_id')
            ->where('session_id=?', $sessionId);

        return $db->fetchOne($select);
    }

    public function getVisitorByQuoteId($quoteId)
    {
        $db = $this->_getReadAdapter();

        $select = $db->select()
            ->from($this->getMainTable(), 'item_id')
            ->where('quote_id=?', $quoteId);

        return $db->fetchOne($select);
    }

    public function flushProcessedQuotes($datePoint)
    {
        $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            array(
                'quote_updated_at < ?' => $datePoint
            )
        );

        return $this;
    }
}