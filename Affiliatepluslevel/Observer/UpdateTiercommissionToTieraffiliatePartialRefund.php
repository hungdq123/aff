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

class UpdateTiercommissionToTieraffiliatePartialRefund extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $creditmemoItem = $observer->getCreditmemoItem();
        $transaction = $observer->getTransaction();
        $orderItem = $creditmemoItem->getOrderItem();
        $affiliateplusCommissionItem = explode(",", $orderItem->getAffiliateplusCommissionItem());

        $tierTransactions = $this->_getTierTransactions($transaction);

        try {
            $account = $this->_accountFactory->create()->setStoreId($transaction->getStoreId());
            foreach ($tierTransactions as $tierTransaction) {
                if ($tierTransaction->getLevel()) {
                    $totalCommissionItemLevel = $affiliateplusCommissionItem[$tierTransaction->getLevel()];
                    $commission = $totalCommissionItemLevel * $creditmemoItem->getQty() / $orderItem->getQtyOrdered();

                    $account->load($tierTransaction->getTierId());
                    if ($account->getData('balance') <= $commission)
                        $account->setBalance(0)->save();
                    else
                        $account->setBalance($account->getData('balance') - $commission)->save();
//                    $account->setBalance($account->getBalance() - $commission)->save();
                }
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            die('d');
        }

    }
}