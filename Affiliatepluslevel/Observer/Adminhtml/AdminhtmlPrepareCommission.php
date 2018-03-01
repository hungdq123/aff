<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 11:18
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AdminhtmlPrepareCommission extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $transaction = $observer->getTransaction();
        $tierTransaction = $this->_getTierTransactions($transaction)
            ->addFieldToFilter('level', 0)
            ->getFirstItem();
        if ($tierTransaction && $tierTransaction->getId()) {
            $transaction->setRealTotalCommission(
                $tierTransaction->getCommission() + $tierTransaction->getCommissionPlus()
            );
        }
    }
}