<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 10:16
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AddTabToTransaction extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }
        $block = $observer->getEvent()->getForm();
        $block->addTab('tier_section', array(
            'label' => __("Tier's transactions"),
            'title' => __("Tier's transactions"),
            'url' => $block->getUrl('affiliateplusadmin/transaction/tier', array(
                '_current' => true,
                'transaction_id' => $block->getRequest()->getParam('transaction_id'),
                'store' => $block->getRequest()->getParam('store')
            )),
            'class' => 'ajax',
            'after' => 'main_section',
        ));
        return $this;
    }
}