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

class CalculateProgramTierCommission extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return;
        }

        $customerId = $this->_sessionCustomer->getCustomer()->getId();
        if ($customerId) {
            $accountIdByCustomerId = $this->_accountCollectionFactory->create()
                ->addFieldToFilter('customer_id', $customerId)
                ->getFirstItem()
                ->getId();
        } else {
            $accountIdByCustomerId = '';
        }
        $account = $observer->getAccount();
        $accountId = $account->getId();
        $item = $observer->getItem();
        $program = $observer->getProgram();
        $commissionObj = $observer->getCommissionObj();

        $maxCommission = $commissionObj->getProfit();
        $commission = $commissionObj->getCommission();

        $baseItemsPrice = $commissionObj->getBaseItemPrice();       // Changed By Adam 22/07/2014
        $affiliateplusCommissionItem = $commissionObj->getAffiliateplusCommissionItem();    // Changed By Adam 14/08/2014

        $tierCommission = array(
            '1' => array(
                'account' => $accountId,
                'commission' => $commission,
            )
        );
        $tierId = $this->_helperTier->getToptierIdByTierId($accountId);
        if ($this->_helperAffCookie->getNumberOrdered()) {
            if ($program->getUseTierConfig()) {
                $isUseSectier = $this->_helperTierCTier->getConfig('use_sec_tier');
                if (!$isUseSectier) {
                    $tierRates = $this->_helperTierCTier->getTierCommissionRates();
                } else {
                    $tierRates = $this->_helperTierCTier->getSecTierCommissionRates($program->getStoreId());
                }
            } else if ($program->getUseSecTier()) {
                $tierRates = $this->_helperTierCTier->getSecTierProgramCommissionRates($program);
            } else {
                $tierRates = $this->_helperTierCTier->getTierProgramCommissionRates($program);
            }
        } else {
            $tierRates = $this->_helperTierCTier->getTierProgramCommissionRates($program);
        }
        $maxLevel = $this->_helperTierCTier->getProgramMaxLevel($program);
        for ($i = 2; $i <= $maxLevel; $i++) {
            if (!$tierId || $commission >= $maxCommission)
                break;
            if ($this->_isTierRecivedCommission($tierId, $item->getStoreId()) && isset($tierRates[$i])) {
                $tierRate = $tierRates[$i];
                if ($tierRate['value'] > 0) {
                    /* Changed By Adam: Calculate commission for whole cart 22/07/2014 */
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
                    if ($tierComm && $tierId != $accountIdByCustomerId) {
                        $tierCommission[$i] = array(
                            'account' => $tierId,
                            'commission' => $tierComm,
                        );
                        $affiliateplusCommissionItem .= $tierComm . ",";
                        $commission += $tierComm;
                    }
                }
            }
            $tierId = $this->_helperTier->getToptierIdByTierId($tierId);
        }
        $commissionObj->setTierCommission($tierCommission);

        $commissionObj->setCommission($commission);

        $commissionObj->setAffiliateplusCommissionItem($affiliateplusCommissionItem);       // Changed By Adam 14/08/2014: Invoice tung phan

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