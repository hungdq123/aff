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

class CalculateTierCommission extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /*catch line 907 function _salesOrderPlaceAfter in observer SalesOrderSaveAfter*/
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $account = $observer->getAccount();
        $accountId = $account->getId();
        $item = $observer->getItem();
        $commissionObj = $observer->getCommissionObj();

        $maxCommission = $commissionObj->getProfit();
        $commission = $commissionObj->getCommission();
        $baseItemsPrice = $commissionObj->getBaseItemPrice();       // Changed By Adam 22/07/2014
        // Changed By Adam 14/08/2014: Invoice tung phan
        $affiliateplusCommissionItem = $commissionObj->getAffiliateplusCommissionItem();
        $tierCommission = array(
            '1' => array(
                'account' => $accountId,
                'commission' => $commission,
            )
        );

        $tierId = $this->_helperTier->getToptierIdByTierId($accountId);
        if(isset($tierId)){

        if ($this->_helperAffCookie->getNumberOrdered() && $this->_helperTierCTier->getConfig('use_sec_tier')
        ) {
            $tierRates = $this->_helperTierCTier->getSecTierCommissionRates();
        } else {
            $tierRates = $this->_helperTierCTier->getTierCommissionRates();
        }
        $maxLevel = $this->_helperTierCTier->getMaxLevel();
        for ($i = 2; $i <= $maxLevel; $i++) {
            if (!$tierId || $commission >= $maxCommission)
                break;
            if ($this->_isTierRecivedCommission($tierId, $item->getStoreId()) && isset($tierRates[$i])) {
                $tierRate = $tierRates[$i];
                if ($tierRate['value'] > 0) {
                    /* Changed By Adam: Commission for whole cart 22/07/2014 */
                    if ($tierRate['type'] == 'cart_fixed') {
                        $tierPrice = $item->getQtyOrdered() * $item->getBasePrice() - $item->getBaseDiscountAmount() - $item->getBaseAffiliateplusAmount();
                        $tierRate['value'] = min($tierRate['value'], $baseItemsPrice);
                        $tierComm = $tierPrice * $tierRate['value'] / $baseItemsPrice;
                    } elseif ($tierRate['type'] == 'fixed') {
                        $tierComm = $item->getQtyOrdered() * $tierRate['value'];
                    } else {
                        $tierComm = $maxCommission * $tierRate['value'] / 100;
                    }
                    if ($commission + $tierComm > $maxCommission)
                        $tierComm = $maxCommission - $commission;
                    if ($tierComm) {
                        // Changed By Adam 14/08/2014: Invoice tung phan
                        $affiliateplusCommissionItem .= $tierComm . ",";

                        $tierCommission[$i] = array(
                            'account' => $tierId,
                            'commission' => $tierComm,
                        );
                        $commission += $tierComm;
                    }
                }
            }
            $tierId = $this->_helperTier->getToptierIdByTierId($tierId);
        }

        $commissionObj->setTierCommission($tierCommission);
        $commissionObj->setCommission($commission);

        // Changed By Adam 14/08/2014: Invoice tung phan
        $commissionObj->setAffiliateplusCommissionItem($affiliateplusCommissionItem);
    }

    }

    /**
     *
     * @param type $tierId
     * @param type $storeId
     * @return boolean
     */
    protected function _isTierRecivedCommission($tierId, $storeId = null) {
        if (!$storeId)
            $storeId = Mage::app()->getStore()->getId();
        $account = $this->_accountFactory->create()
            ->setStoreId($storeId)
            ->load($tierId);
        if ($account->getStatus() == 1) {
            $customerId = $this->_sessionCustomer->getCustomerId();
            if ($account->getCustomerId() != $customerId)
                return true;
        }
        return false;
    }
}