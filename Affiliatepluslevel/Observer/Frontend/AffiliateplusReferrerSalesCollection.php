<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 09:31
 */

namespace Magestore\Affiliatepluslevel\Observer\Frontend;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AffiliateplusReferrerSalesCollection extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $collection = $observer->getCollection();
        $storeId = $this->_storeManager->getStore()->getId();
        $accountId = $this->_sessionModel->getAccount()->getAccountId();
        $transactionTable = $this->_resource->getTableName('magestore_affiliatepluslevel_transaction');

        $collection->getSelect()->reset(\Zend_Db_Select::WHERE);
        if ($this->_helperaffconfig->getSharingConfig('balance', $storeId) == 'store') {
            $collection->addFieldToFilter('store_id', Mage::app()->getStore()->getId());
        } elseif ($this->_helperaffconfig->getSharingConfig('balance', $storeId) == 'website') {
            $storeIds = $this->_helperaffaccount->getStoreIdsByWebsite();
            $collection->addFieldToFilter('store_id', array('in' => $storeIds));
        }
        // $collection->addFieldToFilter('type', '3');

        if (!$this->_helperTier->isPluginEnabled())
            //  Doesn't show transaction in frontend if admin disable tier commission plugin from back-end
            $condition = "main_table.tier_id=$accountId";
        else
            $condition = "ts.tier_id=$accountId OR (ts.tier_id IS NULL AND main_table.account_id = $accountId )";

//        Fix loi khong show transaction o frontend mac du dang co transaction
//        $condition = "ts.tier_id=$accountId OR (ts.tier_id IS NULL AND main_table.account_id = $accountId )";
        $collection->getSelect()
            ->joinLeft(array('ts' => $transactionTable), "ts.transaction_id = main_table.transaction_id", array('level' => 'level'))
            ->columns('IFNULL(ts.commission, main_table.commission) as commission')
            ->columns('IFNULL(ts.commission_plus, main_table.commission_plus) AS commission_plus')
            ->columns('IFNULL(ts.commission_plus, main_table.percent_plus) AS percent_plus')
            ->where($condition);

    }
}