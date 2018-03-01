<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 21/04/2017
 * Time: 14:00
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

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AddTabToAccount extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }
        $block = $observer->getEvent()->getForm();
        if($block->getRequest()->getParam('account_id')){

            $block->addTab('tier_section', array(
                'label' => __("Tier Affiliates"),
                'title' => __("Tier Affiliates"),
                'url' => $block->getUrl('affiliateplusadmin/account/tier', array(
                    '_current' => true,
                    'account_id' => $block->getRequest()->getParam('account_id'),
                    'store' => $block->getRequest()->getParam('store')
                )),
                'class' => 'ajax',
                'after' => 'payment_section',
            ));
        }
        return $this;
    }
}