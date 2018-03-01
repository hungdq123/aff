<?php

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
 * @package     Magestore_Affiliateplus
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Affiliateplus\Model\Total\Invoice;

class Affiliateplus extends \Magento\Sales\Model\Order\Total\AbstractTotal{
    /**
     * @var \Magestore\Affiliateplus\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    public function __construct(
        \Magestore\Affiliateplus\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data=[]
    )
    {
        parent::__construct($data);
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
    }

    public function collect(\Magento\Sales\Model\Order\Invoice $invoice){
        if(!$this->_helper->isAffiliateModuleEnabled()){
            return $this;
        }
        $order = $invoice->getOrder();
        $baseDiscount = 0;
        $discount = 0;

        foreach ($invoice->getAllItems() as $item) {
            if ($item->getOrderItem()->isDummy()) {
                continue;
            }
            $orderItem = $item->getOrderItem();

            $orderItemDiscount = (float)$orderItem->getAffiliateplusAmount();
            $baseOrderItemDiscount = (float)$orderItem->getBaseAffiliateplusAmount();

            $orderItemQty = $orderItem->getQtyOrdered();
            if ($orderItemDiscount && $orderItemQty) {
                $discount -= $orderItemDiscount * $item->getQty() / $orderItemQty;
                $baseDiscount -= $baseOrderItemDiscount * $item->getQty() / $orderItemQty;
            }
        }
        /* Changed By Adam 30/09/2014: to solve the problem:
         * invoice san pham ko phai affiliate nhung van hien discount
         */
//        if (!floatval($baseDiscount)) {
//            $order = $invoice->getOrder();
//            $baseDiscount = $order->getBaseAffiliateplusDiscount();
//            $discount = $order->getAffiliateplusDiscount();
//        }
        if (floatval($baseDiscount)){
            $baseDiscount = $invoice->roundPrice($baseDiscount);
            $discount = $invoice->roundPrice($discount);

            $invoice->setBaseAffiliateplusDiscount($baseDiscount);
            $invoice->setAffiliateplusDiscount($discount);

            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseDiscount);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $discount);
        }
        return $this;
    }
}