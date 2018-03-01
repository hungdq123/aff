<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 14:57
 */
namespace Magestore\Affiliatepluslevel\Controller\Adminhtml\Account;

/**
 * Class Tier
 * @package Magestore\Affiliatepluslevel\Controller\Adminhtml\Account
 */
class ChangeToptier extends \Magestore\Affiliatepluslevel\Controller\Adminhtml\AbstractAction
{
    /**
     * Execute action
     */
    public function execute()
    {
        if (!$this->_helperData->isPluginEnabled()) {
            return $this;
        }

        $accountId = $this->getRequest()->getParam('account_id');
        $account = $this->_accountFactory->create()->load($accountId);
        $tier = $this->_tierCollectionFactory->create()
            ->addFieldToFilter('tier_id', $accountId)
            ->getFirstItem();
        if ($tier && $tier->getId())
            $level = $tier->getLevel();
        else
            $level = 0;
        $result['account_name'] = $account->getName();
        $result['account_id'] = $account->getId();
        $result['level'] = $level;
        return $this->getResponse()->setBody(\Zend_Json::encode($result));
    }
}