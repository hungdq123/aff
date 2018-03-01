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

/**
 * Class Account
 * @package Magestore\Affiliateplusprogram\Block\Adminhtml\Program\Edit\Tab
 */
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
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;
    /**
     * @var \Magestore\Affiliatepluslevel\Helper\Data
     */
    protected $_helper;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magestore\Affiliatepluslevel\Helper\Data $helper,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magestore\Affiliateplus\Model\ResourceModel\Account\CollectionFactory $accountCollectionFactory,
        array $data = array()
    )
    {
        parent::__construct($context, $backendHelper, $data);
        $this->_objectManager = $objectManager;
        $this->_accountCollectionFactory = $accountCollectionFactory;
        $this->_helper = $helper;
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
        $this->setDefaultSort('account_id');
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

    //return category collection filtered by store
    protected function _prepareCollection()
    {
        $accountId	= $this->getRequest()->getParam('account_id');
        $storeId = (int)$this->getRequest()->getParam('store');
        $fullTierIds = $this->_helper->getFullTierIds($accountId, $storeId);

        $collection = $this->_accountCollectionFactory->create()
            ->addFieldToFilter('account_id', array('in' => $fullTierIds));

        $tierTable = $this->_resource->getTableName('magestore_affiliatepluslevel_tier');

        $collection->getSelect()
            ->join($tierTable, "$tierTable.tier_id = main_table.account_id", array('toptier_id' => "$tierTable.toptier_id", 'level'=> 'level'));

        if($storeId)
            $collection->setStoreId($storeId);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $currencyCode =$this->_getStore()->getBaseCurrency()->getCode();
        $prefix = 'tier_grid_';
        $this->addColumn(
            $prefix.'account_id',
            [
                'header'    => __('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'account_id',
                'type'             => 'number',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'filter_index'	=> 'main_table.account_id'
            ]
        );

        $this->addColumn(
            $prefix.'name',
            [
            'header'    => __('Name'),
            'align'     =>'left',
            'index'     => 'name',
            ]
        );


        $this->addColumn(
            $prefix.'email',
            [
            'header'    => __('Email'),
            'index'     => 'email',
            ]
        );

        $this->addColumn(
            $prefix.'balance',
            [
            'header'    => __('Balance'),
            'width'     => '100px',
            'align'     =>'right',
            'index'     => 'balance',
            'type'		=> 'price',
            'currency_code' => $currencyCode,
                'filter_index'	=> 'main_table.balance',
            ]
        );

        $this->addColumn(
            $prefix.'total_commission_received',
            [
            'header'    => __('Total Commission'),
            'width'     => '100px',
            'align'     =>'right',
            'index'     => 'total_commission_received',
            'type'		=> 'price',
            'currency_code' => $currencyCode,
                'filter_index'	=> 'main_table.total_commission_received',
            ]
        );

        $this->addColumn(
            $prefix.'total_paid',
            [
            'header'    => __('Total Paid'),
            'width'     => '100px',
            'align'     =>'right',
            'index'     => 'total_paid',
            'type'		=> 'price',
            'currency_code' => $currencyCode,
                'filter_index'	=> 'main_table.total_paid',
            ]
        );

        $this->addColumn(
            $prefix.'level',
            [
            'header'    => __('Level'),
            'align'     =>'right',
            'index'     => 'level',
            ]
        );

        $this->addColumn(
            $prefix.'status',
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

        $this->addColumn(
            $prefix.'approved',
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

    public function getGridUrl(){
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/tier', array(
                '_current'=>true,
                'id'=>$this->getRequest()->getParam('id'),
                'store'	=> $this->getRequest()->getParam('store')
            ));

    }

    public function getRowUrl($row) {
        $id = $row->getId();
        return $this->getUrl('affiliateplusadmin/account/edit', array(
            'account_id' => $id,
        ));
    }

}