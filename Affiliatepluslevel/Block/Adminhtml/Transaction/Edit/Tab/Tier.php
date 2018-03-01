<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 18:24
 */

namespace Magestore\Affiliatepluslevel\Block\Adminhtml\Transaction\Edit\Tab;

class Tier extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;
    /**
     * @var \Magestore\Affiliateplus\Model\ResourceModel\Account\CollectionFactory
     */
    protected $_accountCollectionFactory;
    /**
     * Affiliate Account Collection Factory
     * @var \Magestore\Affiliatepluslevel\Model\ResourceModel\Transaction\CollectionFactory
     */
    protected $_tierTransactionCollectionFactory;
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magestore\Affiliateplus\Model\ResourceModel\Account\CollectionFactory $accountCollectionFactory,
        \Magestore\Affiliatepluslevel\Model\ResourceModel\Transaction\CollectionFactory $tierTransactionCollectionFactory,
        array $data = array()
    )
    {
        parent::__construct($context, $backendHelper, $data);
        $this->_objectManager = $objectManager;
        $this->_accountCollectionFactory = $accountCollectionFactory;
        $this->_tierTransactionCollectionFactory = $tierTransactionCollectionFactory;
        $this->_resource = $resourceConnection;
        $this->_eventManager = $context->getEventManager();
        $this->_storeManager = $context->getStoreManager();
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('tiergrid');
        $this->setDefaultSort('real_level');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }


    protected function _prepareCollection()
    {
        $transactionId	= $this->getRequest()->getParam('transaction_id');
        $storeId = $this->getRequest()->getParam('store');

        $tierTable = $this->_resource->getTableName('magestore_affiliatepluslevel_tier');
        $accountTable = $this->_resource->getTableName('magestore_affiliateplus_account');

        $collection = $this->_tierTransactionCollectionFactory->create()
            ->addFieldToFilter('transaction_id', $transactionId);

        if($storeId)
            $collection->addFieldToFilter('store_id', $storeId);

        $collection->getSelect()
            ->columns('(main_table.level + 1) AS real_level')
            ->joinLeft($tierTable, "$tierTable.tier_id = main_table.tier_id", array(/* 'level'=>'level' */))
            ->joinLeft($accountTable, "$accountTable.account_id = main_table.tier_id",
                array('name' => 'name', 'account_id' => "$accountTable.account_id", 'email' => 'email'));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $currencyCode = $this->_storeManager->getStore()->getBaseCurrency()->getCode();


        $this->addColumn(
            'name',
            [
            'header'    => __('Name'),
            'align'     =>'left',
            'index'     => 'name',
            ]
        );


        $this->addColumn(
            'email',
            [
            'header'    => __('Email'),
            'width'     => '250px',
            'index'     => 'email',
            ]
        );

        $this->addColumn(
            'real_level',
            [
            'header'    => __('Level'),
            'align'     =>'right',
            'index'     => 'real_level',
            'filter_index' => 'main_table.level',
                'filter_condition_callback' => array($this->_objectManager->get('Magestore\Affiliatepluslevel\Observer\Adminhtml\AddColumnToAccountGrid'), 'filterLevelAddCommission'),
            ]
        );

        $this->addColumn(
            'commission',
            [
            'header'    => __('Commission'),
            'align'     => 'right',
            'index'     => 'commission',
            'type'		=> 'price',
                'filter_index'	=> 'main_table.commission',
            'currency_code' => $currencyCode,
            ]
        );

        $this->_eventManager->dispatch('affiliatepluslevel_transaction_prepare_columns',
            [
                'grid'  => $this->setData('affiliatepluslevel_currency_code', $currencyCode)
            ]
        );

    }

    //return url
    public function getGridUrl(){
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/tierGrid', array(
                '_current'=>true,
                'id'=>$this->getRequest()->getParam('id'),
            ));

    }

    public function getRowUrl($row) {
        $id = $row->getAccountId();
        return $this->getUrl('affiliateplusadmin/account/edit', array(
            'id' => $id,
        ));
    }
}