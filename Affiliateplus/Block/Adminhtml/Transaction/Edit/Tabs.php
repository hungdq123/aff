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
 * @package     Magestore_Affiliateplus
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Affiliateplus\Block\Adminhtml\Transaction\Edit;

/**
 * Grid Grid
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    protected $_blockFactory;
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;
    /**
     * Tabs constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        array $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $authSession, $data);
        $this->_eventManager = $context->getEventManager();
        $this->_blockFactory = $blockFactory;
    }
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('general_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Transaction Information'));
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeToHtml()
    {
        $id = $this->getRequest()->getParam('transaction_id');
        $this->_eventManager->dispatch('affiliateplus_adminhtml_add_transaction_tab', ['form' => $this, 'transaction_id' => $id]);
        /**
         * add tab
         */

        $this->addTab(
            'main_section',
            [
                'label'   => __('General Information'),
                'title'   => __('General Information'),
                'content' => $this->getChildHtml('affiliateplus_transaction_edit_tab_general'),
                'active'  => true
            ]
        );
        
        $this->setActiveTab('main_section');
        return parent::_beforeToHtml();
    }


    /**
     * @param string $tabId
     * @param array|\Magento\Framework\DataObject $tab
     * @param string $afterTabId
     * @throws \Exception
     */
    public function addTabAfter($tabId, $tab, $afterTabId)
    {
        $this->addTab($tabId, $tab);
        $this->_tabs[$tabId]->setAfter($afterTabId);
    }
}
