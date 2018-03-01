<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 10:10
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AfterSaveAccount extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $data = $observer->getPostData();
        $account = $observer->getAccount();
        try {
            if (isset($data['toptier_id']) && $data['toptier_id']) {
                if (empty($data['level']))
                    $data['level'] = $this->_helperTier->getAccountLevel($data['toptier_id']) + 1;
                $tier = $this->_tierCollectionFactory->create()
                    ->addFieldToFilter('tier_id', $account->getId())
                    ->getFirstItem()
                    ->setTierId($account->getId())
                    ->setToptierId($data['toptier_id'])
                    ->setLevel($data['level'])
                    ->save();
                $level = $data['level'];
            } else {
                $tier = $this->_tierCollectionFactory->create()
                    ->addFieldToFilter('tier_id', $account->getId())
                    ->getFirstItem();
                if ($tier->getId())
                    $tier->delete();
                $level = 0;
            }
            // Update tiers level
            $topTierIds = array($account->getId());
            while ($topTierIds) {
                $tiers = $this->_tierCollectionFactory->create()
                    ->addFieldToFilter('toptier_id', array('in' => $topTierIds));
                $topTierIds = array();
                $level++;
                foreach ($tiers as $tier) {
                    $topTierIds[] = $tier->getTierId();
                    $tier->setData('level', $level)->save();
                }
            }
        } catch (Exception $e) {

        }
    }
}