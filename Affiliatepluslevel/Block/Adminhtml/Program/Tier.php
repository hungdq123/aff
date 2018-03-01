<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 10:40
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml\Program;

/**
 * Class Index
 * @package Sample\Gridpart2\Controller\Adminhtml\Template
 */
class Tier extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Initialize block template
     */

    protected $_template = 'Magestore_Affiliatepluslevel::tier.phtml';
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * AbstractRenderer constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $storeManager;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element){
        $this->setElement($element);
        return $this->_toHtml();
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element){
        $id = $element->getHtmlId();
        $html = '<tr><td class="label"><label for="'.$id.'">'.$element->getLabel().'</label></td>';
        $html .= '<td class="value">'.$this->_getElementHtml($element).$element->getAfterElementHtml().'</td>';
        return $html;
    }

    public function getHtmlId(){
        return 'grid_tier_commission';
    }

    public function getMaxLevel(){
        $data = $this->getProgramData();
        $_maxLevel = isset($data['max_level']) ? intval($data['max_level']) : 1;
        return ($_maxLevel > 0) ? $_maxLevel : 1;
    }

    public function getArrayRows(){
        if ($this->hasData('_array_rows_cache')) return $this->getData('_array_rows_cache');

        $result = array();
        $element = $this->getElement();
        if ($element->getValue() && is_array($element->getValue())){
            foreach ($element->getValue() as $rowId => $row){
                foreach ($row as $key => $value) {
                    $row[$key] = $this->htmlEscape($value);
                }
                $row['_id'] = $rowId;
                $result[$rowId] = new Varien_Object($row);
            }
        }
        $this->setData('_array_rows_cache',$result);

        return $this->getData('_array_rows_cache');
    }

    public function getDefaultCommission(){
        $data = $this->getProgramData();
        return isset($data['commission']) ? $data['commission'] : 0;
    }

    public function getDefaultCommissionType(){
        $data = $this->getProgramData();
        return isset($data['commission_type']) ? $data['commission_type'] : 'percentage';
    }
}