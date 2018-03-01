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

class ReduceTransaction extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $transaction = $observer->getTransaction();
        $creditmemo = $observer->getCreditmemo();
        $tierTransactions = $this->_getTierTransactions($transaction);


        $commissionObj = $observer->getCommissionObj();
        $baseReduce = $commissionObj->getBaseReduce();
        $totalReduce = $commissionObj->getTotalReduce();

        try {
            foreach ($tierTransactions as $tierTransaction) {
                $transactionTotal = 0;
                $reduceCommission = 0;
                foreach ($creditmemo->getAllItems() as $item) {
                    $orderItem = $item->getOrderItem();
                    $affiliateplusCommissionItem = explode(",", $orderItem->getAffiliateplusCommissionItem());
                    $totalCommissionItemLevel = $affiliateplusCommissionItem[$tierTransaction->getLevel()];
                    $transactionTotal += $totalCommissionItemLevel * ($item->getQty()) / $orderItem->getQtyOrdered();
                    $reduceCommission += $totalCommissionItemLevel * $item->getQty() / $orderItem->getQtyOrdered();
                }
                if ($tierTransaction->getCommission() <= $transactionTotal) {
                    $tierTransaction->setCommission(0)->save();     // Adam: 22/09/2014: Miss ham save() nen bi sai ket qua khi refund
                } else {
                    $tierTransaction->setCommission($tierTransaction->getCommission() - $transactionTotal)->save();
                }
                $totalCommission = $reduceCommission + $tierTransaction->getCommissionPlus() * $item->getQty() / $orderItem->getQtyOrdered();

                $commissionObj->setBaseReduce($reduceCommission);
                $commissionObj->setTotalReduce($totalCommission);

                // send email to tier
                if ($tierTransaction->getLevel() > 0)
                    $tierTransaction->sendMailReducedTransactionToAccount($transaction, $reduceCommission, $totalCommission);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            die('x');
        }
    }
}