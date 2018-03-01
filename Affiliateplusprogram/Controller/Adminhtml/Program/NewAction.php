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
 * @package     Magestore_Affiliateplusprogram
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Affiliateplusprogram\Controller\Adminhtml\Program;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class NewAction
 * @package Magestore\Affiliateplusprogram\Controller\Adminhtml\Program
 */
class NewAction extends \Magestore\Affiliateplusprogram\Controller\Adminhtml\AbstractAction
{
    /**
     * Execute action
     */
    public function execute()
    {

        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);

        return $resultForward->forward('edit');
    }
}
