<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 09:03
 */

namespace Magestore\Affiliatepluslevel\Observer\Frontend;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AffiliateplusPrepareProgram extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }
        $info = $observer->getInfo();

        if ($info->getId()) {
            $info->setTierCommission($this->_helperTierCTier->prepareLabelRates($this->_helperTierCTier->getTierProgramCommissionRates($info)));
            $info->setSecTierCommission($this->_helperTierCTier->prepareLabelRates(
                $this->_helperTierCTier->getSecTierProgramCommissionRates($info)
            ));
        } else {
            $info->setTierCommission($this->_helperTierCTier->prepareLabelRates($this->_helperTierCTier->getTierCommissionRates()));
            $info->setSecTierCommission($this->_helperTierCTier->prepareLabelRates(
                $this->_helperTierCTier->getSecTierCommissionRates()
            ));
        }
        if (is_array($info->getTierCommission())) {
            $info->setLevelCount(count($info->getTierCommission()));
        }
        if (is_array($info->getSecTierCommission())) {
            $info->setSecLevelCount(count($info->getSecTierCommission()));
        }

    }
}