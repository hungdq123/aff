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
namespace Magestore\Affiliatepluslevel\Controller\Adminhtml;

use \Zend\Uri\Uri;
/**
 * Class AbstractAction
 * @package Magestore\Affiliatepluslevel\Controller\Adminhtml
 */
abstract class AbstractAction extends \Magento\Backend\App\Action
{
    /**
     * @var \Magestore\Affiliatepluslevel\Model\ResourceModel\Tier\CollectionFactory $tierCollectionFactory
     */
    protected $_tierCollectionFactory;
    /**
     * @var \Magestore\Affiliateplus\Model\AccountFactory $accountFactory
     */
    protected $_accountFactory;
    /**
     * @var \Magestore\Affiliatepluslevel\Helper\Data $helperData
     */
    protected $_helperData;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $_dateFilter;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_massActionFilter;


    /**
     * AbstractAction constructor.
     * @param Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magestore\Affiliateplusprogram\Controller\Adminhtml\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Ui\Component\MassAction\Filter $massActionFilter,
        \Magestore\Affiliatepluslevel\Helper\Data $helperData,
        \Magestore\Affiliatepluslevel\Model\ResourceModel\Tier\CollectionFactory $tierCollectionFactory,
        \Magestore\Affiliateplus\Model\AccountFactory $accountFactory,
        Uri $uri
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_storeManager = $context->getStoreManager();
        $this->_coreRegistry = $context->getRegistry();
        $this->_objectManager = $context->getObjectManager();
        $this->_dateFilter = $dateFilter;
        $this->_massActionFilter = $massActionFilter;
        $this->_helperData = $helperData;
        $this->_tierCollectionFactory = $tierCollectionFactory;
        $this->_accountFactory = $accountFactory;
        $this->_uri = $uri;
        parent::__construct($context);
    }

    /**
     * @param $model
     * @return mixed
     */
    public function getModel($model){
        return $this->_objectManager->create($model);
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _createMainCollection()
    {
        /** @var \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection */
        $collection = $this->_objectManager->create('Magestore\Affiliateplusprogram\Model\ResourceModel\Program\Collection');
        if (!$collection instanceof \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(
                    '%1 isn\'t instance of Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection',
                    get_class($collection)
                )
            );
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_Affiliateplus::program');
    }
}
