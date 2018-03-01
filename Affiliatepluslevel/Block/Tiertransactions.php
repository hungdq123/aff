<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 10:04
 */
namespace Magestore\Affiliatepluslevel\Block;

/**
 * @category Magestore
 * @package  Magestore_Affiliatepluslevel
 * @module   Affiliateplus
 * @author   Magestore Developer
 */
class Tiertransactions extends AbstractTemplate
{
    /**
     * get Helper
     *
     * @return Magestore_Affiliateplus_Helper_Config
     */
    public function _getHelper() {
        return $this->helper('Magestore\Affiliateplus\Helper\Config');
    }

    protected function _construct() {
        parent::_construct();
        $account = $this->_sessionModel->getAccount();

        $accountId = $account->getAccountId();
        $transactionTable = $this->_resource->getTableName('affiliateplus_transaction');

        $collection = $this->_tierTransactionCollectionFactory->create()
            ->addFieldToFilter('tier_id', $accountId);


        $collection->getSelect()
            ->columns('(main_table.level + 1) AS real_level')
            ->joinLeft($transactionTable, "$transactionTable.transaction_id = main_table.transaction_id", array('account_name' => 'account_name',
                'account_email' => 'account_email',
                'order_id' => 'order_id',
                'order_number' => 'order_number',
                'order_item_ids' => 'order_item_ids',
                'order_item_names' => 'order_item_names',
                'total_amount' => 'total_amount',
                'discount' => 'discount',
                'created_time' => 'created_time',
                'status' => 'status',
                'store_id' => 'store_id',
            ));

        $collection->setOrder('created_time', 'DESC');
        /* edit by blanka */
        if ($this->_getHelper()->getSharingConfig('balance') == 'store')
            $collection->addFieldToFilter('store_id', $this->_storeManager->getStore()->getId());
        elseif ($this->_getHelper()->getSharingConfig('balance') == 'website') {
            $websiteId = $this->_storeManager->getWebsite()->getId();
            $storeIds = $this->helper('Magestore\Affiliateplus\Helper\Account')->getStoreIdsByWebsite($websiteId);
            $collection->addFieldToFilter('store_id', array('in' => $storeIds));
        }
        /* end edit */

        $collection->addFieldToFilter('level', array('neq' => 0));

        $this->setCollection($collection);
    }

    public function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'tiertransactions_pager')
            ->setTemplate('affiliateplus/html/pager.phtml')
            ->setCollection($this->getCollection());
        $this->setChild('tiertransactions_pager', $pager);

        $grid = $this->getLayout()->createBlock('affiliateplus/grid', 'tiertransactions_grid');

        $grid->addColumn('created_time', array(
            'header' => __('Date'),
            'index' => 'created_time',
            'type' => 'date',
            'format' => 'medium',
            'align' => 'left',
            'searchable' => true,
            'width' => '150px'
        ));

        $grid->addColumn('account_name', array(
            'header' => __('Affiliates'),
            'index' => 'account_name',
            'align' => 'left',
            'render' => 'getAffiliatesName'
        ));

        $grid->addColumn('order_item_names', array(
            'header' => __('Products'),
            'index' => 'order_item_names',
            'align' => 'left',
            'render' => 'getFrontendProductHtmls',
        ));

        $grid->addColumn('total_amount', array(
            'header' => __('Total') . '<br />' . __('Amount'),
            'align' => 'left',
            'type' => 'baseprice',
            'index' => 'total_amount'
        ));

        $grid->addColumn('commission', array(
            'header' => __('Commission'),
            'align' => 'left',
            'type' => 'baseprice',
            'index' => 'commission'
        ));

        $this->_eventManager->dispatch('affiliatepluslevel_prepare_sales_columns_plus',
            [
            'grid' => $grid
            ]
        );

        $grid->addColumn('real_level', array(
            'header' => __('Level'),
            'align' => 'left',
            'index' => 'real_level'
        ));

        $grid->addColumn('status', array(
            'header' => __('Status'),
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => $this->__('Complete'),
                2 => $this->__('Pending'),
                3 => $this->__('Canceled'),
                4 => $this->__('On Hold'),
            ),
            'width' => '95px',
            'searchable' => true,
        ));

        $this->setChild('tiertransactions_grid', $grid);
        return $this;
    }

    public function getNoNumber($row) {
        return sprintf('#%1', $row->getId());
    }

    public function getAffiliatesName($row) {
        if ($row->getRealLevel() > 2) {
            return $row->getAccountName();
        }
        return sprintf("%s <a href='mailto:%s'>%s</a>", $row->getAccountName(), $row->getAccountEmail(), $row->getAccountEmail());
    }

    public function getFrontendProductHtmls($row) {
        return $this->helper('Magestore\Affiliateplus\Helper\Data')->getFrontendProductHtmls($row->getData('order_item_ids'));
    }

    public function getPagerHtml() {
        return $this->getChildHtml('tiertransactions_pager');
    }

    public function getGridHtml() {
        return $this->getChildHtml('tiertransactions_grid');
    }

    protected function _toHtml() {
        $this->getChild('tiertransactions_grid')->setCollection($this->getCollection());
        return parent::_toHtml();
    }

    public function getStatisticInfo() {
        $accountId = $this->_sessionModel->getAccount()->getId();
        $storeId = $this->_storeManager->getStore()->getId();
        $scope = $this->getConfig('affiliateplus/account/balance', $storeId);

        $transactionTable = $this->_resource->getTableName('affiliateplus_transaction');
        $collection = $this->_tierTransactionCollectionFactory->create()
            ->addFieldToFilter('tier_id', $accountId)
            ->addFieldToFilter('level', array('neq' => 0));

        $collection->getSelect()
            ->join($transactionTable, "$transactionTable.transaction_id=main_table.transaction_id", array('status' => 'status'));

        /* edit by blanka */
        if ($storeId && $scope == 'store')
            $collection->addFieldToFilter('store_id', $storeId);
        elseif ($scope == 'website') {
            $websiteId = $this->_storeManager->getWebsite()->getId();
            $storeIds = $this->helper('Magestore\Affiliateplus\Helper\Account')->getStoreIdsByWebsite($websiteId);
            $collection->addFieldToFilter('store_id', array('in' => $storeIds));
        }
        /* end edit */

        $totalCommission = 0;
        foreach ($collection as $item) {
            if ($item->getStatus() == 1)
                $totalCommission += $item->getCommission() + $item->getCommissionPlus();
        }
        return array(
            'number_commission' => count($collection),
            'transactions' => $this->__('Tier Transactions'),
            'commissions' => $totalCommission,
            'earning' => $this->__('Tier Earnings')
        );
    }
}