<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 10:24
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AddColumnAccountTransactionGrid extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $grid = $observer->getGrid();
        $grid->addColumn('level', array(
            'header' => __('Level'),
            'width' => '50px',
            'align' => 'right',
            'index' => 'level'
        ));

    }
}