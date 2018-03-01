<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 21/04/2017
 * Time: 18:01
 */

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
 * @package     Magestore_Affiliatepluslevel
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Affiliatepluslevel\Model;

/**
 * Class AbtractModel
 * @package Magestore\Affiliatepluslevel\Model
 */
class AbstractModel extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magestore\Affiliateplus\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magestore\Affiliateplus\Helper\Config
     */
    protected $_helperConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resourceConnection;
    /**
     * Affiliate Model Account Factory
     * @var \Magestore\Affiliateplus\Model\AccountFactory
     */
    protected $_accountFactory;
    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $_currencyFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
    /**
     * AbtractModel constructor.
     * @param \Magestore\Affiliateplusprogram\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magestore\Affiliateplusprogram\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magestore\Affiliateplus\Helper\Data $helper,
        \Magestore\Affiliateplus\Helper\Config $helperConfig,
        \Magestore\Affiliateplus\Model\AccountFactory $accountFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,

        array $data = array()
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_objectManager = $context->getObjectManager();
        $this->_storeManager =  $context->getStoremanager();
        $this->_eventManager = $context->getEventManager();
        $this->_resourceConnection = $resourceConnection;
        $this->_helper = $helper;
        $this->_helperConfig = $helperConfig;
        $this->_accountFactory = $accountFactory;
        $this->_currencyFactory = $currencyFactory;
        $this->_messageManager = $messageManager;
        $this->_transportBuilder = $transportBuilder;
        $this->_urlBuilder = $urlBuilder;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function getModel($model){
        return $this->_objectManager->create($model);
    }
    /**
     * @param $router
     * @param $params
     * @return string
     */
    public function getUrl($router, $params)
    {
        return $this->_urlBuilder->getUrl($router, $params);
    }

}
