<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 14:48
 */
namespace Magestore\Affiliatepluslevel\Controller\Adminhtml\Affiliatepluslevel\Account;

/**
 * Class Tier
 * @package Magestore\Affiliatepluslevel\Controller\Adminhtml\Account
 */
class Toptier extends \Magestore\Affiliatepluslevel\Controller\Adminhtml\AbstractAction
{
    /**
     * Execute action
     */
    public function execute()
    {
        if (!$this->_helper->isPluginEnabled()) {
            return;
        }
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}