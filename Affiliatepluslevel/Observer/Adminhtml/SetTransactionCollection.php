<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 10:27
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class SetTransactionCollection extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $grid = $observer->getGrid();
        $accountId = $observer->getAccountId();
        $storeId = $observer->getStore();

        $transactionTable = $this->_resource->getTableName('magestore_affiliatepluslevel_transaction');
        $collection = $this->_affTransactionCollectionFactory->create();
        $collection->getSelect()
            ->joinLeft(array('ts' => $transactionTable), "ts.transaction_id = main_table.transaction_id", array('level' => 'level'))
            ->columns('IFNULL(ts.commission, main_table.commission) as commission')
            ->where("ts.tier_id=$accountId OR (ts.tier_id IS NULL AND main_table.account_id = $accountId )");

        if ($storeId)
            $collection->addFieldToFilter('store_id', $storeId);

        $grid->setCollection($collection);

    }
}