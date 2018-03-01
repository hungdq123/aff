<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 15:28
 */
namespace Magestore\Affiliatepluslevel\Controller\Adminhtml\Account;


/**
 * Class Tier
 * @package Magestore\Affiliatepluslevel\Controller\Adminhtml\Account
 */
class ToptierGrid extends \Magestore\Affiliatepluslevel\Controller\Adminhtml\AbstractAction
{
    /**
     * Execute action
     */
    public function execute()
    {
        if (!$this->_helperData->isPluginEnabled()) {
            return $this;
        }
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
