<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 08:24
 */
/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Affiliatepluslevel
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Affiliatepluslevel\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class CancelTransaction extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $transaction = $observer->getTransaction();
        $transactionStatus = $observer->getStatus();
        $commission = $observer->getCommission();
        $tierTransactions = $this->_getTierTransactions($transaction);

        try {
            $account = $this->_accountFactory->create()->setStoreId($transaction->getStoreId());
            foreach ($tierTransactions as $tierTransaction) {

                if ($transactionStatus == 1) {
                    $account->load($tierTransaction->getTierId());

                    if ($tierTransaction->getLevel() == 0) {
                        $balanceAdded = $transaction->getCommission() + $transaction->getCommissionPlus() + $transaction->getCommission() * $transaction->getPercentPlus() / 100;
                        $balance = $account->getData('balance') + $balanceAdded - $tierTransaction->getCommission() - $tierTransaction->getCommissionPlus();
                        //                    $balance = $account->getBalance() + $balanceAdded - $tierTransaction->getCommission() - $tierTransaction->getCommissionPlus();
                    } else {
                        $balance = $account->getData('balance') - $tierTransaction->getCommission() - $tierTransaction->getCommissionPlus();
                        //                    $balance = $account->getBalance() - $tierTransaction->getCommission() - $tierTransaction->getCommissionPlus();
                    }

                    $account->setBalance($balance)
                        ->save();
                }

                $tierTransaction->setCommission(0)
                    ->setCommissionPlus(0)
                    ->save();
                //send mail Complete to account
                if ($tierTransaction->getLevel() > 0)
                    $tierTransaction->sendMailUpdatedTransactionToAccount($transaction, false);
            }
        } catch (Exception $e) {

        }

    }
}