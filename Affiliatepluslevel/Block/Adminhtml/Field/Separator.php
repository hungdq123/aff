<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 13:25
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml\Field;

class Separator extends \Magento\Config\Block\System\Config\Form\Field
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
            . '<td class="label" colspan="1">';
        $marginTop = $element->getComment() ? $element->getComment() : '0px';
        $html .= '<div class="aff-separator-configuration-content">';
        $html .= $element->getLabel();
        $html .= '</div></td>';
        $html .= '<td></td><td></td><td></td></tr>';

        return $html;
    }
}