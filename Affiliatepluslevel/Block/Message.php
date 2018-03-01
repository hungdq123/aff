<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 5/5/2017
 * Time: 3:46 PM
 */
namespace Magestore\Affiliatepluslevel\Block;
/**
 * Class Message
 * @package Magestore\Affiliateplusprogram\Block
 */
class Message extends \Magento\Framework\View\Element\Messages {


    protected function _prepareLayout()
    {
        $this->addMessages($this->messageManager->getMessages(true));
        parent::_prepareLayout();
    }
}