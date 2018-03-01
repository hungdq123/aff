<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 16:21
 */

namespace Magestore\Affiliatepluslevel\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class CreatedTransactionAndRecalculateCommission extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return;
        }

        $transaction = $observer->getTransaction();
        $tierCommissions = $transaction->getTierCommissions();

        $tierTransactions = array();
        $isStandardTransaction = true;
        foreach ($tierCommissions as $itemId => $tierCommission) {
            foreach ($tierCommission as $level => $accComm) {
                if ($level > 1 && $isStandardTransaction)
                    $isStandardTransaction = false;
                if (isset($tierTransactions[$accComm['account']])) {
                    $tierTransactions[$accComm['account']]['commission'] += $accComm['commission'];
                } else {
                    $tierTransactions[$accComm['account']] = array(
                        'tier_id' => $accComm['account'],
                        'transaction_id' => $transaction->getId(),
                        'level' => $level - 1,
                        'commission' => $accComm['commission'],
                    );
                }
            }
        }
        if ($isStandardTransaction)
            return $this;
        $model = $this->_tierTransactionFactory->create();
        foreach ($tierTransactions as $tierTransaction) {
            $model->setData($tierTransaction);
            $tierCommPlus = $transaction->getPercentPlus() * $model->getCommission() / 100;
            $tierCommPlus += $transaction->getCommissionPlus() * $model->getCommission() / $transaction->getCommission();
            $model->setCommissionPlus($tierCommPlus);
            try {
                $model->setId(null)->save();
                if ($model->getLevel() > 0) {
                    /*send transaction email when guest create order refer link tier affiliate*/
                    $model->sendMailNewTransactionToAccount($transaction);
                }
            } catch (Exception $e) {

            }
        }

        return $this;
    }
}