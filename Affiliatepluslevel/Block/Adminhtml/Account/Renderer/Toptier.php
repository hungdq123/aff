<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 16:00
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml\Account\Renderer;

class Toptier extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /* Render Grid Column*/
    public function render(\Magento\Framework\DataObject $row)
    {
        if($row->getToptierId())
            return sprintf('
				<a href="%s" title="%s">%s</a>',
                $this->getUrl('affiliateplusadmin/account/edit/', array('_current'=>true, 'account_id' => $row->getToptierId())),
                __('View Account Detail'),
                $row->getToptierName()
            );
        else
            return __('N/A');
    }
}