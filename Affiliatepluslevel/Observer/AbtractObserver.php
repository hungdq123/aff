<?php

/**
 * Magestore.
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

use Zend\Uri\Uri;
/**
 * Class AbtractObserver
 * @package Magestore\Affiliatepluslevel\Observer
 */
class AbtractObserver {
    /**
     * @var  \Magento\Backend\Helper\Data $helperBackend
     */
    protected $_helperBackend;
    /**
     * @var \Magestore\Affiliateplus\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magestore\Affiliateplus\Helper\Cookie $helperAffCookie
     */
    protected $_helperAffCookie;
    /**
     * @var \Magestore\Affiliatepluslevel\Helper\Data $helperTier
     */
    protected $_helperTier;
    /**
     * @var \Magestore\Affiliatepluslevel\Helper\Tier $helperTierLevel
     */
    protected $_helperTierCTier;
    /**
     * @var \Magestore\Affiliateplus\Helper\Account
     */
    protected $_helperaffaccount;
    /**
     * @var \Magestore\Affiliateplus\Helper\Config
     */
    protected $_helperaffconfig;
    /**
     * @var \Magestore\Affiliatepluslevel\Model\TierFactory
     */
    protected $_tierFactory;
    /**
     * @var \Magestore\Affiliatepluslevel\Model\ResourceModel\Tier\CollectionFactory
     */
    protected $_tierCollectionFactory;
    /**
     * @var \Magestore\Affiliatepluslevel\Model\TransactionFactory $tierTransactionFactory
     */
    protected $_tierTransactionFactory;
    /**
     * @var \Magestore\Affiliatepluslevel\Model\ResourceModel\Transaction\CollectionFactory $tierTransactionCollectionFactory
     */
    protected $_tierTransactionCollectionFactory;
    /**
     * @var \Magestore\Affiliateplusprogram\Model\ProgramFactory
     */
    protected $_programFactory;
    /**
     * @var \Magestore\Affiliateplusprogram\Model\ResourceModel\Program\CollectionFactory
     */
    protected $_programCollectionFactory;
    /**
     * @var \Magestore\Affiliateplusprogram\Model\AccountFactory
     */
    protected $_programAccountFactory;
    /**
     * @var \Magestore\Affiliateplusprogram\Model\ResourceModel\Account\CollectionFactory
     */
    protected $_programAccountCollectionFactory;
    /**
     * @var \Magestore\Affiliateplusprogram\Model\JoinedFactory
     */
    protected $_programJoinedFactory;
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
     * @var \Magestore\Affiliateplus\Model\ResourceModel\Account\CollectionFactory $accountCollectionFactory
     */
    protected $_accountCollectionFactory;
    /**
     * @var \Magestore\Affiliateplus\Model\ResourceModel\Transaction\CollectionFactory $afftransactionCollectionFactory
     */
    protected $_affTransactionCollectionFactory;
    /**
     * @var \Magestore\Affiliateplus\Model\Session $sessionModel,
     */
    protected $_sessionModel;
    /**
     * @var \Magestore\Affiliateplus\Helper\Cookie $cookieHelper
     */
    protected $_cookieHelper;
    /**
     * @var \Magestore\Affiliateplus\Block\AbstractTemplate
     */
    protected $_abtractTemplate;
    /**
     * @var \Magento\Framework\App\Request\RequestInterface
     */
    protected $_request;
    /**
     * @var  \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;
    /**
     * @var \Magento\Backend\Model\Session\Quote
     */
    protected $_backendSessionQuote;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var Uri
     */
    protected $_uri;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_sessionCustomer;
    /**
     * @var Magento\Backend\Block\AbstractBlock
     */
    protected $_abstractbackendmagento;

    /**
     * AbtractObserver constructor.
     * @param \Magestore\Affiliatepluslevel\Helper\Data $helper
     * @param \Magestore\Affiliateplusprogram\Model\TransactionFactory $transactionFactory
     * @param \Magestore\Affiliateplusprogram\Model\ResourceModel\Transaction\CollectionFactory $programTransactionCollectionFactory
     * @param \Magestore\Affiliateplusprogram\Model\ProgramFactory $programFactory
     * @param \Magestore\Affiliateplusprogram\Model\ResourceModel\Program\CollectionFactory $programCollectionFactory
     * @param \Magestore\Affiliateplusprogram\Model\AccountFactory $programAccountFactory
     * @param \Magestore\Affiliateplusprogram\Model\ResourceModel\Account\CollectionFactory $programAccountCollectionFactory
     * @param \Magestore\Affiliateplusprogram\Model\JoinedFactory $programJoinedFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magestore\Affiliateplus\Model\AccountFactory $accountFactory
     * @param \Magestore\Affiliateplus\Helper\Cookie $cookieHelper
     * @param \Magestore\Affiliateplus\Block\AbstractTemplate $abstractTemplate
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Backend\Model\Session\Quote $backendSessionQuote
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magestore\Affiliateplus\Helper\Data $helper,
        \Magestore\Affiliatepluslevel\Helper\Data $helperTier,
        \Magestore\Affiliatepluslevel\Helper\Tier $helperTierCTier,
        \Magestore\Affiliateplus\Helper\Cookie $helperAffCookie,
        \Magestore\Affiliatepluslevel\Model\TierFactory $tierFactory,
        \Magestore\Affiliateplus\Model\ResourceModel\Transaction\CollectionFactory $afftransactionCollectionFactory,
        \Magestore\Affiliateplus\Model\ResourceModel\Account\CollectionFactory $accountCollectionFactory,
        \Magestore\Affiliateplus\Model\Session $sessionModel,
        \Magestore\Affiliatepluslevel\Model\ResourceModel\Tier\CollectionFactory $tierCollectionFactory,
        \Magestore\Affiliatepluslevel\Model\ResourceModel\Transaction\CollectionFactory $tierTransactionCollectionFactory,
        \Magestore\Affiliateplusprogram\Model\ProgramFactory $programFactory,
        \Magestore\Affiliateplusprogram\Model\ResourceModel\Program\CollectionFactory $programCollectionFactory,
        \Magestore\Affiliateplusprogram\Model\AccountFactory $programAccountFactory,
        \Magestore\Affiliateplusprogram\Model\ResourceModel\Account\CollectionFactory $programAccountCollectionFactory,
        \Magestore\Affiliateplusprogram\Model\JoinedFactory $programJoinedFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magestore\Affiliateplus\Model\AccountFactory $accountFactory,
        \Magestore\Affiliatepluslevel\Model\TransactionFactory $tierTransactionFactory,
        \Magestore\Affiliateplus\Helper\Cookie $cookieHelper,
        \Magestore\Affiliateplus\Helper\Account $helperaffaccount,
        \Magento\Backend\Helper\Data $helperBackend,
        \Magestore\Affiliateplus\Helper\Config $helperaffconfig,
        \Magestore\Affiliateplus\Block\AbstractTemplate $abstractTemplate,
        \Magento\Backend\Block\AbstractBlock $abstractbackendBlock,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Backend\Model\Session\Quote $backendSessionQuote,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $sessionCustomer,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        Uri $uri
    )
    {
        $this->_helper = $helper;
        $this->_helperTier = $helperTier;
        $this->_helperTierCTier = $helperTierCTier;
        $this->_helperAffCookie = $helperAffCookie;
        $this->_tierFactory = $tierFactory;
        $this->_tierCollectionFactory = $tierCollectionFactory;
        $this->_affTransactionCollectionFactory = $afftransactionCollectionFactory;
        $this->_tierTransactionCollectionFactory = $tierTransactionCollectionFactory;
        $this->_accountCollectionFactory = $accountCollectionFactory;
        $this->_sessionModel = $sessionModel;
        $this->_tierTransactionFactory = $tierTransactionFactory;
        $this->_programFactory = $programFactory;
        $this->_programCollectionFactory = $programCollectionFactory;
        $this->_programAccountFactory = $programAccountFactory;
        $this->_programAccountCollectionFactory = $programAccountCollectionFactory;
        $this->_programJoinedFactory = $programJoinedFactory;
        $this->_storeManager = $storeManager;
        $this->_eventManager = $eventManager;
        $this->_accountFactory = $accountFactory;
        $this->_cookieHelper = $cookieHelper;
        $this->_helperaffaccount = $helperaffaccount;
        $this->_helperBackend = $helperBackend;
        $this->_helperaffconfig = $helperaffconfig;
        $this->_abtractTemplate = $abstractTemplate;
        $this->_abstractbackendmagento = $abstractbackendBlock;
        $this->_request = $request;
        $this->_resource = $resourceConnection;
        $this->_customerFactory = $customerFactory;
        $this->_backendSessionQuote = $backendSessionQuote;
        $this->_checkoutSession = $checkoutSession;
        $this->_objectManager = $objectManager;
        $this->_uri = $uri;
        $this->_sessionCustomer = $sessionCustomer;
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore() {
        return $this->_storeManager->getStore();
    }

    /**
     * @return \Magento\Framework\App\Request
     */
    public function getRequest(){
        return $this->_request;
    }

    /**
     * Get Tax Helper
     * @return mixed
     */
    public function getTaxHelper(){
        if(!$this->_taxHelper){
            $this->_taxHelper = $this->_objectManager->create('Magento\Tax\Helper\Data');
        }
        return $this->_taxHelper;
    }

    /**
     * @param $address
     * @param $product
     * @param $store
     * @return float|int
     */
    public function getItemRateOnQuote($address, $product, $store) {
        $taxClassId = $product->getTaxClassId();
        if ($taxClassId) {
            $request = $this->getCalculationTaxModel()->getRateRequest(
                $address,
                $address->getQuote()->getBillingAddress(),
                $address->getQuote()->getCustomerTaxClassId(),
                $store
            );
            $rate = $this->getCalculationTaxModel()
                ->getRate($request->setProductClassId($taxClassId));
            return $rate;
        }
        return 0;
    }

    /**
     * @return \Magento\Tax\Model\Calculation
     */
    public function getCalculationTaxModel(){
        return $this->_objectManager->create('Magento\Tax\Model\Calculation');
    }

    /**
     * @param $price
     * @param $rate
     * @return float
     */
    public function calTax($price, $rate) {
        return $this->round($this->getCalculationTaxModel()->calcTaxAmount($price, $rate, true, false));
    }

    /**
     * @param $price
     * @return float
     */
    public function round($price) {
        return $this->getCalculationTaxModel()->round($price);
    }

    /**
     * @param $model
     * @return mixed
     */
    public function getModel($model){
        return $this->_objectManager->create($model);
    }

    /**
     *
     * @param type $transaction
     * @return type
     */
    protected function _getTierTransactions($transaction) {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }
        $collection = $this->_tierTransactionCollectionFactory->create()
            ->addFieldToFilter('transaction_id', $transaction->getId())
            ->setOrder('id', 'asc');
        return $collection;
    }
}