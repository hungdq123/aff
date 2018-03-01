<?php

/**
 * Magestore
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
 * @package     Magestore_Affiliateplus
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Affiliatepluslevel\Helper;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class HelperAbstract extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $_assetRepo;
    /**
     * @var \Magento\Framework\Simplexml\Config
     */
    protected $_config;
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $_sessionManagerInterface;
    /**
     * @var \Magento\Framework\CurrencyInterface
     */
    protected $_currencyInterface;

    /**
     * @var \Magestore\Affiliateplus\Helper\Data $helperData
     */
    protected $_helperData;
    /**
     * @var \Magestore\Affiliateplus\Helper\Account $helperAccount
     */
    protected $_helperAccount;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $_moduleManager;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Cookie Manager Interface
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $_cookieManager;

    /**
     * Public Cookie Metadata
     * @var \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata
     */
    protected $_publicCookieMetadata;

    /**
     * Cookie metadata factory
     *
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    protected $_cookieMetadataFactory;

    /**
     * Request Interface
     * @var \Magento\Framework\App\RequestInterface $request
     */
    protected $_request;

    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Backend\Model\Session\Quote
     */
    protected $_backendQuoteSession;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;
    /**
     * @var \Magestore\Affiliatepluslevel\Model\Session
     */
    protected $_sessionModel;

    /**
     * Affiliate Model Account Factory
     * @var \Magestore\Affiliatepluslevel\Model\AccountFactory
     */
    protected $_tierFactory;

    /**
     * Affiliate Account Collection Factory
     * @var \Magestore\Affiliatepluslevel\Model\ResourceModel\Account\CollectionFactory
     */
    protected $_tierCollectionFactory;

    /**
     * @var
     */
    protected $_backendUrl;
    /**
     * @var \Magento\Framework\HTTP\Adapter\CurlFactory
     */
    protected $_curlFactory;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    protected $_storeFactory;

    /**
     * Block constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManagerInterface,
        \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata $publicCookieMetadata,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\SessionManagerInterface $sessionManagerInterface,
        \Magestore\Affiliateplus\Helper\Data $helperData,
        \Magestore\Affiliateplus\Helper\Account $helperAccount,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Backend\Model\Session\Quote $backendQuoteSession,
        \Magestore\Affiliatepluslevel\Model\TierFactory $tierFactory,
        \Magestore\Affiliatepluslevel\Model\ResourceModel\Tier\CollectionFactory $tierCollectionFactory,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Simplexml\Config $config,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Magento\Directory\Helper\Data $directoryHelper


    ) {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_productFactory = $productFactory;
        $this->_moduleManager = $context->getModuleManager();
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        $this->_helperData = $helperData;
        $this->_helperAccount = $helperAccount;
        $this->_cookieManager = $cookieManagerInterface;
        $this->_publicCookieMetadata = $publicCookieMetadata;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_request = $context->getRequest();
        $this->_sessionManagerInterface = $sessionManagerInterface;
        $this->_checkoutSession = $checkoutSession;
        $this->_backendQuoteSession = $backendQuoteSession;
        $this->_tierFactory = $tierFactory;
        $this->_tierCollectionFactory = $tierCollectionFactory;
        $this->_config = $config;
        $this->_layout = $layout;
        $this->_assetRepo = $assetRepo;
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->_priceCurrency = $priceCurrency;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_localeDate = $localeDate;
        $this->_curlFactory = $curlFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_filesystem = $filesystem;
        try {
            $this->_currencyInterface = $this->_objectManager->get('\Magento\Framework\CurrencyInterface');
        } catch (\Zend_Currency_Exception $e) {
            $this->_currencyInterface = $localeCurrency->getCurrency($directoryHelper->getBaseCurrencyCode());
        }

    }

    /**
     * get store config
     *
     * @param $key
     * @param null $store
     * @return mixed
     */
    public function _getConfig($key, $store = null) {
        return $this->_scopeConfig->getValue(
            $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Check the module is enable or not
     * @param $moduleName Magestore_Affiliatepluslevel
     * @return bool
     */
    public function isModuleEnabled($moduleName){
        return $this->_moduleManager->isEnabled($moduleName);
    }
}