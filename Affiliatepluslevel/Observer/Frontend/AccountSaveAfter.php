<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 08:24
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

namespace Magestore\Affiliatepluslevel\Observer\Frontend;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AccountSaveAfter extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $toptiersInfo = $this->_cookieHelper->getAffiliateInfo();
        $account = $observer->getAffiliateplusAccount();

        $isNew = $account->isObjectNew();
        $toptier = null;
        if ($isNew) {
            foreach ($toptiersInfo as $code => $toptierInfo) {// get first element
                $toptier = $toptierInfo['account'];
                $toptierLevel = $this->_helperTier->getAccountLevel($toptier->getId());
                break;
            }

            if ($toptier && $toptier->getId()) {
                try {
                    $tier = $this->_tierFactory->create();
                    $tier->setToptierId($toptier->getId())
                        ->setTierId($account->getId())
                        ->setLevel($toptierLevel + 1);
                        $tier->save();
                } catch (Exception $e) {

                }
            }
        }
    }
}