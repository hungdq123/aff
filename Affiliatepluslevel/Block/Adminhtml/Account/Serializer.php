<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 20/04/2017
 * Time: 15:54
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml\Account;

class Serializer extends \Magento\Framework\View\Element\Template
{

    /**
     * Initialize block template
     */

    protected $_template = 'Magestore_Affiliatepluslevel::account/serializer.phtml';
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

    public function initSerializerBlock($gridName,$hiddenInputName)
    {
        $grid = $this->getLayout()->getBlock($gridName);
        $this->setGridBlock($grid)
            ->setInputElementName($hiddenInputName);
    }
}