<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 11:11
 */
namespace Magestore\Affiliatepluslevel\Controller\Tiercommission;

/**
 * Action ListTierTransaction
 */
class ListTierTransaction extends \Magestore\Affiliatepluslevel\Controller\AbstractAction
{
    /**
     * Execute action
     */
    public function execute()
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this->_redirect('affiliateplus/index/index');
        }

        if ($this->_getAccountHelper()->accountNotLogin())
            return $this->_redirect('affiliateplus/account/login');
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Commissions'));
        $this->renderLayout();
    }

    protected function _getAccountHelper(){
        return $this->_accountHelper;
    }
}