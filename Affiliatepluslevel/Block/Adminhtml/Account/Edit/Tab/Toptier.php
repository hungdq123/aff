<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 16:06
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml\Account\Edit\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Toptier extends \Magento\Backend\Block\Widget\Grid\Extended
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
     * @var \Magestore\Affiliateplus\Model\AccountFactory
     */
    protected $_accountFactory;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;
    /**
     * Affiliate Account Collection Factory
     * @var \Magestore\Affiliatepluslevel\Model\ResourceModel\Tier\CollectionFactory
     */
    protected $_tierCollectionFactory;
    /**
     * Affiliate Account Collection Factory
     * @var \Magestore\Affiliatepluslevel\Model\ResourceModel\TierFactory $tierFactory
     */
    protected $_tierFactory;
    /**
     * @var \Magestore\Affiliateplus\Model\ResourceModel\Account\CollectionFactory
     */
    protected $_accountCollectionFactory;
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;
    /**
     * @var \Magestore\Affiliatepluslevel\Helper\Data $helper;
     */
    protected $_helper;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magestore\Affiliateplus\Model\AccountFactory $accountFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magestore\Affiliatepluslevel\Model\ResourceModel\Tier\CollectionFactory $tierCollectionFactory,
        \Magestore\Affiliatepluslevel\Model\ResourceModel\TierFactory $tierFactory,
        \Magestore\Affiliateplus\Model\ResourceModel\Account\CollectionFactory $accountCollectionFactory,
        \Magestore\Affiliatepluslevel\Helper\Data $helper,
        array $data = array()
    )
    {
        parent::__construct($context, $backendHelper, $data);
        $this->_objectManager = $objectManager;
        $this->_resource = $resourceConnection;
        $this->_accountCollectionFactory = $accountCollectionFactory;
        $this->_tierCollectionFactory = $tierCollectionFactory;
        $this->_tierFactory = $tierFactory;
        $this->_accountFactory = $accountFactory;
        $this->_eventManager = $context->getEventManager();
        $this->_storeManager = $context->getStoreManager();
        $this->_helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('toptiergrid');
        $this->setDefaultSort('account_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->_getSelectedAccount()) {
            $this->setDefaultFilter(array('in_toptiers'=>1));
        }
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
        $storeId = $this->getRequest()->getParam('store');
        $accountId = $this->getRequest()->getParam('account_id');
        $collection = $this->_accountCollectionFactory->create();
        $tierTable = $this->_resource->getTableName('magestore_affiliatepluslevel_tier');
        $collection->getSelect()
            ->joinLeft($tierTable, "$tierTable.tier_id = main_table.account_id", array('level'=>"IFNULL($tierTable.level, 0)"))
        ;
        if($this->getRequest()->getParam('account_id'))
            $collection->addFieldToFilter('account_id', array('neq' => $accountId));


        $tierIds = $this->_objectManager->create('Magestore\Affiliatepluslevel\Helper\Data')->getFullTierIds($accountId, $storeId);

        if(count($tierIds))
            $collection->addFieldToFilter('account_id', array('nin' => $tierIds));


        $this->setCollection($collection);
        return parent::_prepareCollection();
    }


    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_toptiers') {
            $accountId = $this->_getSelectedAccount();
            if ($column->getFilter()->getValue()){
                $this->getCollection()->addFieldToFilter('account_id', $accountId);
            } else {
                if($accountId) {
                    $this->getCollection()->addFieldToFilter('account_id', array('neq'=>$accountId));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_toptiers',
            [
                'header_css_class'  => 'a-center',
                'type'              => 'radio',
                'html_name'         => 'in_toptiers',
                'align'             => 'center',
                'index'             => 'account_id',
                'values'            => array($this->_getSelectedAccount()),
            ]
        );

        $currencyCode =$this->_getStore()->getBaseCurrency()->getCode();
        $this->addColumn('account_id',
            [
                'header'    => __('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'account_id',
                'filter_index'	=> 'main_table.account_id',
            ]
        );

        $this->addColumn('name',
            [
                'header'    => __('Name'),
                'align'     =>'left',
                'index'     => 'name',
                'filter_index'	=> 'main_table.name',
            ]
        );


        $this->addColumn('email',
            [
                'header'    => __('Email'),
                'width'     => '300px',
                'index'     => 'email',
                'filter_index'	=> 'main_table.email'
            ]
        );

        $this->addColumn('level',
            [
                'header'    => __('Level'),
                'align'     =>'right',
                'index'     => 'level',
                'width'     => '50px',
                'filter_condition_callback' => array($this,'filterLevelAffiliateAccount'),
            ]
        );

        $this->addColumn('status',
            [
                'header'    => __('Status'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'status',
                'type'      => 'options',
                'filter_index'	=> 'main_table.status',
                'options'   => array(
                    1 => 'Enabled',
                    2 => 'Disabled',
                ),
            ]
        );

        $this->addColumn('approved',
            [
                'header'    => __('Approved'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'approved',
                'type'      => 'options',
                'filter_index'	=> 'main_table.approved',
                'options'   => array(
                    1 => 'Yes',
                    2 => 'No',
                ),
            ]
        );

    }

    //return url
    public function getGridUrl(){
        $accountId= $this->getRequest()->getParam('account_id');
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/toptierGrid', array(
                '_current'=>true,
                'toptier_id'=>$this->_helper->getToptierIdByTierId($accountId),
                'store'	=> $this->getRequest()->getParam('store')
            ));

    }

    public function getRowUrl($row) {
        $account_id = $row->getAccountId();
        return 'javascript:changeUpTier(this,\''.$account_id.'\')';
    }

    public function getAccount()
    {
        return $this->_accountFactory->create()->load($this->getRequest()->getParam('account_id'));
    }

    protected function _getSelectedAccount(){
        // get top tier of current account
        if ($this->getRequest()->getParam('map_toptier_id') != null) {
            return $this->getRequest()->getParam('map_toptier_id');
        }
        $accountId = $this->getRequest()->getParam('account_id');
        if (!$accountId) return 0;
        
        $getToptierId = $this->getRequest()->getParam('toptier_id');
        return $getToptierId;
    }

    public function getSelectedAccount() {
        return $this->_getSelectedAccount();
    }

    /**
     *
     * @param type $collection
     * @param type $column
     * @return type
     */
    public function filterLevelAffiliateAccount($collection, $column) {
        $tierTable = $this->_resource->getTableName('magestore_affiliatepluslevel_tier');
        $value = $column->getFilter()->getValue();
        if (!isset($value))
            return;
        if ($value == 0) {
            $collection->getSelect()->where("$tierTable.level IS NULL");
            return;
        }
        $collection->addFieldToFilter("$tierTable.level", $value);
    }

}