<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 07:57
 */

namespace Magestore\Affiliatepluslevel\Block;

/**
 * @category Magestore
 * @package  Magestore_Affiliatepluslevel
 * @module   Affiliatepluslevel
 * @author   Magestore Developer
 */
class Tiers extends AbstractTemplate
{

    /**
     * get Helper
     *
     * @return Magestore_Affiliateplus_Helper_Config
     */
    public function _getHelper() {
        return $this->_configHelper;
    }

    protected function _construct() {
        parent::_construct();
        $accountId = $this->_sessionModel->getAccount()->getId();

        $allTierIds = $this->_tierHelperData->getAllTierIds($accountId, $this->_storeManager->getStore()->getId());
        if (count($allTierIds))
            $allTierIdsString = implode(',', $allTierIds);
        else
            $allTierIdsString = 0;

        $tierTable = $this->_resource->getTableName('magestore_affiliatepluslevel_tier');

        $collection = $this->_accountCollectionFactory->create()
            ->setStoreViewId($this->_storeManager->getStore()->getId())
            ->setOrder('created_time', 'DESC');

        $collection->getSelect()
            ->joinLeft($tierTable, "$tierTable.tier_id = main_table.account_id", array('level' => 'level', 'toptier_id' => 'toptier_id'))
            ->where("account_id IN ($allTierIdsString)");

        $request = $this->getRequest();
        if ($request->getParam('joined') == 'desc') {
            $collection->getSelect()->order('created_time DESC');
        } elseif ($request->getParam('joined') == 'asc') {
            $collection->getSelect()->order('created_time ASC');
        } elseif ($request->getParam('level') == 'desc') {
            $collection->getSelect()->order('level DESC');
        } elseif ($request->getParam('level') == 'asc') {
            $collection->getSelect()->order('level ASC');
        } else {
            $collection->getSelect()->order('account_id DESC');
        }

        $this->setCollection($collection);
    }

    public function _prepareLayout() {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager', 'tiers_pager')
            ->setTemplate('Magestore_Affiliateplus::html/pager.phtml')
            ->setCollection($this->getCollection());
        $this->setChild('tiers_pager', $pager);

        $grid = $this->getLayout()->createBlock('Magestore\Affiliateplus\Block\Grid', 'tiers_grid');


        $url = $this->getUrl('affiliateplus/tiercommission/listtier');
        // prepare column
        $grid->addColumn(
            'id',
            [
            'header' => __('No.'),
            'align' => 'left',
            'render' => 'getNoNumber',
            ]
        );

        if ($this->getRequest()->getParam('joined') == 'desc')
            $header = '<a href="' . $url . 'joined/asc" class="sort-arrow-desc" title="' . __('ASC') . '">' . __('Joined time') . '</a>';
        else
            $header = '<a href="' . $url . 'joined/desc" class="sort-arrow-asc" title="' . __('DESC') . '">' . __('Joined time') . '</a>';

        $grid->addColumn(
            'created_time',
            [
            'header' => $header,
            'index' => 'created_time',
            'type' => 'date',
            'format' => 'medium',
            'align' => 'left',
            ]
        );

        $grid->addColumn(
            'name',
            [
            'header' => __('Affiliates'),
            'index' => 'name',
            'align' => 'left',
            'render' => 'getAffiliatesName'
            ]
        );


        if ($this->getRequest()->getParam('level') == 'desc')
            $header = '<a href="' . $url . 'level/asc" class="sort-arrow-desc" title="' . __('ASC') . '">' . __('Level') . '</a>';
        else
            $header = '<a href="' . $url . 'level/desc" class="sort-arrow-asc" title="' . __('DESC') . '">' . __('Level') . '</a>';

        $grid->addColumn(
            'level',
            [
            'header' => $header,
            'index' => 'level',
            'align' => 'left',
            'render' => 'getLevel',
            ]
        );

        $grid->addColumn(
            'sum',
            [
            'header' => __('Commissions'),
            'align' => 'left',
            'index' => 'sum',
            'render' => 'getSum',
            ]
        );

        $grid->addColumn(
            'status',
            [
            'header' => __('Status'),
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => __('Enabled'),
                2 => __('Disabled'),
            )
            ]
        );

        $this->setChild('tiers_grid', $grid);
        return $this;
    }

    public function getNoNumber($row) {
        return sprintf('#%d', $row->getId());
    }

    public function getAffiliatesName($row) {
        if ($row->getLevel() - $this->getCurrentAcountLevel() > 1) {
            return $row->getName();
        }
        return sprintf("%s (<a href='mailto:%s'>%s</a>)", $row->getName(), $row->getEmail(), $row->getEmail());
    }

    public function getCurrentAcountLevel() {
        if (!$this->hasData('current_account_level')) {
            $accountId = $this->_sessionModel->getAccount()->getId();
            $currentAccountLevel = $this->_tierHelperData->getAccountLevel($accountId);
            $this->setData('current_account_level', $currentAccountLevel);
        }
        return $this->getData('current_account_level');
    }

    public function getLevel($row) {
        $currentAccountLevel = $this->getCurrentAcountLevel();
        return $row->getLevel() - $currentAccountLevel + 1;
    }

    public function getSum($row) {
        $accountId = $this->_sessionModel->getAccount()->getId();

        $transactions = $this->_transactionCollectionFactory->create()
            ->addFieldToFilter('account_id', $row->getAccountId())
            ->addFieldToFilter('status', 1);

        $transactionIds = array();
        foreach ($transactions as $transaction) {
            $transactionIds[] = $transaction->getId();
        }

        $tiertransactions = $this->_tierTransactionCollectionFactory->create()
            ->addFieldToFilter('transaction_id', array('in' => $transactionIds))
            ->addFieldToFilter('level', array('neq' => 0))
            ->addFieldToFilter('tier_id', $accountId);
        $commissions = 0;
        foreach ($tiertransactions as $tiertransaction) {
            $commissions += $tiertransaction->getCommission();
        }

        return $this->getFormatedCurrency($commissions);
    }

}
