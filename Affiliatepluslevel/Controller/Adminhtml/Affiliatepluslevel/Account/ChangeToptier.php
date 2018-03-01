<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 14:57
 */
namespace Magestore\Affiliatepluslevel\Controller\Adminhtml\Affiliatepluslevel\Account;

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
        if (!$this->_helper->isPluginEnabled()) {
            return;
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

        $html = '';
        $html .= '<input type="hidden" id="map_toptier_name" value="' . $account->getName() . '" />';
        $html .= '<input type="hidden" id="map_toptier_id" value="' . $account->getId() . '" />';
        $html .= '<input type="hidden" id="map_toptier_level" value="' . $level . '" />';
        $this->getResponse()->setHeader('Content-type', 'application/x-json');
        $this->getResponse()->setBody($html);

    }
}