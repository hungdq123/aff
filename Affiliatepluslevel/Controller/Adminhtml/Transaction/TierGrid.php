<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 21/04/2017
 * Time: 13:39
 */

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
namespace Magestore\Affiliatepluslevel\Controller\Adminhtml\Transaction;


/**
 * Class Account
 * @package Magestore\Affiliatepluslevel\Controller\Adminhtml\Program
 */
class TierGrid extends \Magestore\Affiliatepluslevel\Controller\Adminhtml\AbstractAction
{
    /**
     * Execute action
     */
    public function execute()
    {
        if (!$this->_helperData->isPluginEnabled()) {
            return;
        }
        $this->_view->loadLayout();
        $this->_view->getLayout()->getBlock('transaction.edit.tab.tier')
            ->setPrograms($this->getRequest()->getPost('trantier', null));
        $this->_view->renderLayout();
    }
}
