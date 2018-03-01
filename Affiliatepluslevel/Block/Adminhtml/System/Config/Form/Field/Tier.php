<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 13:54
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml\System\Config\Form\Field;

class Tier extends \Magento\Config\Block\System\Config\Form\Field
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
        
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $storeManager;
    }

    /**
     * @return \Magento\Store\Model\Store
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);

        return $this->_storeManager->getStore($storeId);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element){
        $this->setElement($element);
        return $this->_toHtml();
    }

    public function _getConfig($key, $store = null) {
        return $this->_scopeConfig->getValue($key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }

    public function getHtmlId(){
        return 'affiliateplus_commission_tier_commission';
    }

    public function getMaxLevel(){
        $storeId = $this->getRequest()->getParam('store');
        $_maxLevel = intval($this->_getConfig('affiliateplus/commission/max_level', $storeId));
        return ($_maxLevel > 0) ? $_maxLevel : 1;
    }

    public function getArrayRows(){
        if ($this->hasData('_array_rows_cache')) return $this->getData('_array_rows_cache');

        $result = array();
        $element = $this->getElement();
        if ($element->getValue() && is_array($element->getValue())){
            foreach ($element->getValue() as $rowId => $row){
                foreach ($row as $key => $value) {
                    $row[$key] = $this->escapeHtml($value);
                }
                $row['_id'] = $rowId;
                $result[$rowId] = new \Magento\Framework\DataObject($row);
            }
        }
        $this->setData('_array_rows_cache',$result);

        return $this->getData('_array_rows_cache');
    }

    public function getDefaultCommission(){
        $storeId = $this->getRequest()->getParam('store');
        return $this->_getConfig('affiliateplus/commission/commission_value',$storeId);
    }

    public function getDefaultCommissionType(){
        $storeId = $this->getRequest()->getParam('store');
        return $this->_getConfig('affiliateplus/commission/commission_type', $storeId);
    }


}