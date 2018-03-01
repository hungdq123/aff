<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 11:26
 */
namespace Magestore\Affiliatepluslevel\Controller\Tiercommission;

/**
 * Action ListTierTransaction
 */
class ListTier extends \Magestore\Affiliatepluslevel\Controller\AbstractAction
{
    /**
     * Show all tier affiliates
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page
     */
    public function execute(){
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this->_redirect('affiliateplus/index/index');
        }
        if ($this->_accountHelper->accountNotLogin()){
            return $this->_redirect('affiliateplus/account/login');
        }

        $resultPage = $this->_pageFactory->create();

        $resultPage->getConfig()->getTitle()->set(__('Tier Affiliates'));

        return $resultPage;
    }
}