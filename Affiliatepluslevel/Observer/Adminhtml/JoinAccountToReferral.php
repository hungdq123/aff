<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 10:02
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class JoinAccountToReferral extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $collection = $observer->getCollection();

        $tierTable = $this->_resource->getTableName('magestore_affiliatepluslevel_tier');
        $accountTable = $this->_resource->getTableName('magestore_affiliateplus_account');

        $collection->getSelect()
            ->joinLeft($tierTable, "$tierTable.tier_id = main_table.account_id", array('level' => "IFNULL($tierTable.level, 0)"))
            ->joinLeft($accountTable, "$accountTable.account_id = $tierTable.toptier_id", array('toptier_name' => "IFNULL($accountTable.name, 'N/A')", 'toptier_id' => "$accountTable.account_id"))
        ;
    }
}