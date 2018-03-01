<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 10:40
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml\Field;

/**
 * Class Index
 * @package Sample\Gridpart2\Controller\Adminhtml\Template
 */
class Tiercommission extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Retrieve HTML markup for given form element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $id = $element->getHtmlId();
        $html  = '<tr id="row_' . $id . '">'
            . '<td class="label" colspan="1"></td>';
        $html .= '<td class="value"><a href="http://www.magestore.com/affiliateplus/productfile/index/view/fileid/150/" target="_bank">'.$element->getLabel().'</a>';
        $html .= '</td></tr>';
        return $html;
    }
}