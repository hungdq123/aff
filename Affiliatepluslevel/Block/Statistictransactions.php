<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 10:28
 */
namespace Magestore\Affiliatepluslevel\Block;

/**
 * @category Magestore
 * @package  Magestore_Affiliatepluslevel
 * @module   Affiliateplus
 * @author   Magestore Developer
 */
class Statistictransactions extends AbstractTemplate
{

    public function _prepareLayout() {
        parent::_prepareLayout();
        $this->setTemplate('affiliatepluslevel/statistictransactions.phtml');
        return $this;
    }

    public function getFormatedCurreny($value) {
        $store = Mage::app()->getStore();
        return $store->getBaseCurrency()->format($value);
    }

    public function getInfoStandardCommission() {
        $accountId = $this->getAccount()->getId();
        $storeId = $this->_storeManager->getStore()->getId();
        $scope = $this->getConfig('affiliateplus/account/balance', $storeId);

        $transactionTable = $this->_resource->getTableName('affiliatepluslevel_transaction');
        $collection = $this->_transactionCollectionFactory->create()
            ->addFieldToFilter('account_id', $accountId);
        $collection->getSelect()
            ->joinLeft(array('ts' => $transactionTable), "ts.transaction_id = main_table.transaction_id", array('level' => 'level', 'plus_commission' => 'commission_plus'))
            ->columns("IFNULL(ts.commission, main_table.commission) as commission")
            ->where("ts.tier_id=$accountId OR (ts.tier_id IS NULL AND main_table.account_id = $accountId )");

        if ($storeId && $scope == 'store')
            $collection->addFieldToFilter('store_id', $storeId);

        $totalCommission = 0;
        foreach ($collection as $item) {
            if ($item->getStatus() == 1) {
                $totalCommission += $item->getCommission();
                if ($item->getPlusCommission())
                    $totalCommission += $item->getPlusCommission();
                else
                    $totalCommission += $item->getCommissionPlus() + $item->getCommission() * $item->getPercentPlus() / 100;
            }
        }

        return array(
            'number_commission' => count($collection),
            'commissions' => $totalCommission,
            'total_commission' => $this->getFormatedCurreny($totalCommission)
        );
    }

    public function getInfoTierCommission() {
        $accountId = $this->getAccount()->getId();
        $storeId = $this->_storeManager->getStore()->getId();
        $scope = $this->getConfig('affiliateplus/account/balance', $storeId);

        $transactionTable = $this->_resource->getTableName('affiliateplus_transaction');
        $collection = $this->_tierTransactionCollectionFactory->create()
            ->addFieldToFilter('tier_id', $accountId)
            ->addFieldToFilter('level', array('neq' => 0));

        $collection->getSelect()
            ->join($transactionTable, "$transactionTable.transaction_id=main_table.transaction_id", array('status' => 'status'));


        if ($storeId && $scope == 'store')
            $collection->addFieldToFilter('store_id', $storeId);

        $totalCommission = 0;
        foreach ($collection as $item) {
            if ($item->getStatus() == 1)
                $totalCommission += $item->getCommission() + $item->getCommissionPlus();
        }
        //die();
        return array(
            'number_commission' => count($collection),
            'commissions' => $totalCommission,
            'total_commission' => $this->getFormatedCurreny($totalCommission)
        );
    }

    public function getAccount() {
        return $this->_sessionModel->getAccount();
    }

}