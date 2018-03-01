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

class UpdateTiercommissionToTransactionPartialInvoice extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $transaction = $observer->getTransaction();
        $order = $observer->getOrder();


        $tierTransactions = $this->_getTierTransactions($transaction);
        try {
            foreach ($tierTransactions as $tierTransaction) {
                $transactionTotal = 0;
                foreach ($order->getAllItems() as $item) {
                    if ($item->getAffiliateplusCommission()) {
                        $affiliateplusCommissionItem = explode(",", $item->getAffiliateplusCommissionItem());
                        $totalCommissionItemLevel = $affiliateplusCommissionItem[$tierTransaction->getLevel()];
                        $transactionTotal += $totalCommissionItemLevel * $item->getQtyInvoiced() / $item->getQtyOrdered();
                    }
                }
                if ($transactionTotal)
                    $tierTransaction->setCommission($transactionTotal)->save();

                //send mail Complete to tier
                if ($tierTransaction->getLevel() > 0)
                    $tierTransaction->sendMailUpdatedTransactionToAccount($transaction, true);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            die('z');
        }

    }
}