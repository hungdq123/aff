<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 09:45
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AddColumnToAccountGrid extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $grid = $observer->getGrid();
        $accountTable = $this->_resource->getTableName('magestore_affiliateplus_account');
        $tierTable = $this->_resource->getTableName('magestore_affiliatepluslevel_tier');
        $grid->addColumn('toptier_name', array(
            'header' => __('Upper Tier'),
            'width' => '150px',
            'align' => 'right',
            'index' => 'toptier_name',
            'renderer' => 'Magestore\Affiliatepluslevel\Block\Adminhtml\Account\Renderer\Toptier',
            'filter_index' => "$accountTable.name",
            //'filter_condition_callback' => array(Mage::getSingleton('affiliatepluslevel/observer'),'filterToptierAffiliateAccount'),
        ));

        $grid->addColumn('level', array(
            'header' => __('Level'),
            'align' => 'right',
            'index' => 'level',
            'filter_index' => "$tierTable.level",
            'filter_condition_callback' => array($this->_objectManager->get('Magestore\Affiliatepluslevel\Observer\Adminhtml\AddColumnToAccountGrid'), 'filterLevelAffiliateAccount'),
        ));
    }

    /**
     *
     * @param type $collection
     * @param type $column
     * @return type
     */
    public function filterLevelAffiliateAccount($collection, $column) {
        $tierTable =$this->_resource->getTableName('magestore_affiliatepluslevel_tier');
        $value = $column->getFilter()->getValue();
        if (!isset($value))
            return;
        if ($value == 0) {
            $collection->getSelect()->where("$tierTable.level IS NULL");
            return;
        }
        $collection->addFieldToFilter("$tierTable.level", $value);
    }

    /**
     *
     * @param type $collection
     * @param type $column
     * @return type
     */
    public function filterLevelAddCommission($collection, $column) {
        $tierTransactionTable =$this->_resource->getTableName('magestore_affiliatepluslevel_transaction');
        $value = $column->getFilter()->getValue();
        if (!isset($value))
            return;
        if ($value == 0) {
            $collection->getSelect()->where("main_table.level IS NULL");
            return;
        }
        $subtract_level = $value - 1;
        $collection->addFieldToFilter("main_table.level", $subtract_level);
    }
}