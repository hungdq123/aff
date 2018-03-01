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

class UpdateTiercommissionToTieraffiliatePartialInvoice extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $transaction = $observer->getTransaction();
        $item = $observer->getItem();
        $invoiceItem = $observer->getInvoiceItem();

        $affiliateplusCommissionItem = explode(",", $item->getAffiliateplusCommissionItem());


        $tierTransactions = $this->_getTierTransactions($transaction);
        try {
            $account = $this->_accountFactory->create()->setStoreId($transaction->getStoreId());
            if (!$invoiceItem) {
                foreach ($tierTransactions as $tierTransaction) {
                    if ($tierTransaction->getLevel()) {
                        $totalCommissionItemLevel = $affiliateplusCommissionItem[$tierTransaction->getLevel()];
                        $commission = $totalCommissionItemLevel * ($item->getQtyInvoiced() - $item->getQtyRefunded()) / $item->getQtyOrdered();

                        $account->load($tierTransaction->getTierId());
                        //                    $account->setBalance($account->getBalance() + $commission)->save();

                        $account->setBalance($account->getData('balance') + $commission)->save();
                    }
                }
            } else {

                foreach ($tierTransactions as $tierTransaction) {
                    if ($tierTransaction->getLevel()) {
                        $totalCommissionItemLevel = $affiliateplusCommissionItem[$tierTransaction->getLevel()];
                        $commission = $totalCommissionItemLevel * $invoiceItem->getQty() / $item->getQtyOrdered();

                        $account->load($tierTransaction->getTierId());

                        $account->setBalance($account->getData('balance') + $commission)->save();
                    }
                }
            }
        } catch (Exception $e) {

        }

    }
}