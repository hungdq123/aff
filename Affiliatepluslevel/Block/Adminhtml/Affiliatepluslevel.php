<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 15:01
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml;

class Affiliatepluslevel extends \Magento\Backend\Block\Widget\Grid\Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_account';
        $this->_blockGroup = 'Magestore_Affiliatepluslevel';
        $this->_headerText = __('Item Manager');
        $this->_addButtonLabel = __('Add Item');

        parent::_construct();
    }
}