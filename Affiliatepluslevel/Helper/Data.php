<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 16:47
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
namespace Magestore\Affiliatepluslevel\Helper;

/**
 * Helper Data
 */
class Data extends HelperAbstract
{
    const XML_PATH_ADMIN_EMAIL_IDENTITY = 'trans_email/ident_general';
    /**
     * @var \Magestore\Affiliatepluslevel\Model\Tracking
     */
    protected $_trackingModel;

    /**
     * @var \Magestore\Affiliatepluslevel\Model\Transaction
     */
    protected $_transactionModel;

    /**
     * @var \Magento\Sales\Model\Order;
     */
    protected $_salesOrderModel;

    /**
     * @return bool
     */
    public function isAffiliateModuleEnabled() {
        $storeId = $this->_storeManager->getStore()->getId();
        if($this->_getConfig('affiliateplus/general/enable', $storeId)){
            return true;
        }
        return false;
    }

    /*cac ham nay z la copy va convert tu magento 1 len magento 2*/
    public function getAccountLevel($accountId) {
        $tier = $this->_tierCollectionFactory->create()
            ->addFieldToFilter('tier_id', $accountId)
            ->getFirstItem();
        if ($tier && $tier->getId())
            return $tier->getLevel();
        else
            return 0;
    }
    public function getToptierIdByTierId($tierId) {
        $tier = $this->_tierCollectionFactory->create()
            ->addFieldToFilter('tier_id', $tierId)
            ->getFirstItem();
        if ($tier && $tier->getId())
            return $tier->getToptierId();
        else
            return NULL;
    }
    public function getAllTierIds($toptierId, $storeId) { // tier will recived commission
        return $this->getFullTierIds($toptierId, $storeId);
        $perCommissions = $this->_getConfig('affiliateplus/multilevel/commission_percentage', $storeId);
        $numLevel = count(explode(',', $perCommissions));
        $toptierIds = array($toptierId);
        $allTierIds = array();
        for ($i = 0; $i < $numLevel; $i++) {
            $tiers = $this->_tierCollectionFactory->create()
                ->addFieldToFilter('toptier_id', array('in' => $toptierIds));
            $toptierIds = array();
            foreach ($tiers as $tier) {
                $toptierIds[] = $tier->getTierId();
                $allTierIds[] = $tier->getTierId();
            }
            if (!count($toptierIds))
                break;
        }
        return $allTierIds;
    }
    public function getFullTierIds($toptierId, $storeId) { // all tier unlimit level
        $toptierIds = array($toptierId);
        $allTierIds = array();
        while (true) {
            $tiers = $this->_tierCollectionFactory->create()
                ->addFieldToFilter('toptier_id', array('in' => $toptierIds));
            $toptierIds = array();
            foreach ($tiers as $tier) {
                $toptierIds[] = $tier->getTierId();
                $allTierIds[] = $tier->getTierId();
            }
            if (!count($toptierIds))
                break;
        }
        return $allTierIds;
    }

    public function isPluginEnabled() {
        if(!$this->_helperData->isAffiliateModuleEnabled()) return false;

        $check = $this->_helperData->getConfig('affiliateplus/level/enable');
        return $check;
    }
    public function isModuleDisabled($store = null) {
        if ($this->_helperAccount->accountNotLogin())
            return TRUE;
        $check = $this->_helperData->getConfig('affiliateplus/level/enable', $store);
        return !$check;
    }
    /* end update */
    /*cac function nay z la copy roi convert tu magento 1 len magento 2*/


}
