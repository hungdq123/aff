<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 10:35
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AddTierFieldToProgram extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $form = $observer->getEvent()->getForm();
        $data = $observer->getEvent()->getFormData();
        $fieldset = $observer->getEvent()->getFieldset();


        $inStore = $this->_storeManager->getStore();
        $defaultLabel = __('Use Default');
        $defaultTitle = __('-- Please Select --');
        $scopeLabel = __('STORE VIEW');

       $multilevel_separator = $fieldset->addField(
            'multilevel_separator',
            'text',
            [
                'label' => __('Tier Commission'),
                'title' => __('Tier Commission'),
                'comment' => '10px',
            ]
        );
        $renderer1 = $this->_abstractbackendmagento->getLayout()->createBlock('Magestore\Affiliateplus\Block\Adminhtml\Field\Separator');
        $multilevel_separator->setRenderer($renderer1);

        $inStoreData = isset($data['use_tier_config_in_store']) ? $data['use_tier_config_in_store'] : false;
        $fieldset->addField(
            'use_tier_config',
            'select',
            [
            'name' => 'use_tier_config',
            'label' => __('Use General Configuration'),
            'title' => __('Use General Configuration'),
            'values' => $this->_objectManager->get('Magento\Config\Model\Config\Source\Yesno')->toOptionArray(),
            'disabled' => ($inStore && !$inStoreData),
            'after_element_html' => ($inStore ? '</td><td class="use-default">
			<input id="use_tier_config_default" name="use_tier_config_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($inStoreData ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="use_tier_config_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
          </td>' : '') . '</td><td class="scope-label">
			[' . $scopeLabel . ']<script type="text/javascript">
				function changeTierConfig(){
					var config = $(\'affiliateplusprogram_use_tier_config\').value;
					if (config == 1){
						$(\'affiliateplusprogram_max_level\').parentNode.parentNode.hide();
						$(\'grid_tier_commission\').parentNode.parentNode.hide();
						$(\'grid_sec_tier_commission\').parentNode.parentNode.hide();
						$(\'affiliateplusprogram_use_sec_tier\').parentNode.parentNode.hide();
					}else{
						$(\'affiliateplusprogram_max_level\').parentNode.parentNode.show();
						$(\'grid_tier_commission\').parentNode.parentNode.show();
						$(\'affiliateplusprogram_use_sec_tier\').parentNode.parentNode.show();
                        changeSecTierConfig();
					}
				}
                function changeSecTierConfig() {
                    if ($(\'affiliateplusprogram_use_sec_tier\').value == 1) {
                        $(\'grid_sec_tier_commission\').parentNode.parentNode.show();
                    } else {
                        $(\'grid_sec_tier_commission\').parentNode.parentNode.hide();
                    }
                }
				Event.observe(window,\'load\',changeTierConfig);
			</script>',
            'onchange' => 'changeTierConfig()'
            ]
        );
        $inStoreData = isset($data['max_level_in_store']) ? $data['max_level_in_store'] : false;
        $fieldset->addField(
            'max_level',
            'text',
            [
            'name' => 'max_level',
            'label' => __('Number of Tiers to Enable'),
            'title' => __('Number of Tiers to Enable'),
            'disabled' => ($inStore && !$inStoreData),
            'after_element_html' => $inStore ? '</td><td class="use-default">
			<input id="max_level_default" name="max_level_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($inStoreData ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="max_level_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
          </td><td class="scope-label">
			[' . $scopeLabel . ']
          ' : '</td><td class="scope-label">
			[' . $scopeLabel . ']',
            ]
        );
        $inStoreData = isset($data['tier_commission_in_store']) ? $data['tier_commission_in_store'] : false;
        $fieldset->addField(
            'tier_commission',
            'text',
            [
            'name' => 'tier_commission',
            'label' => __('Tier Commission Value & Type'),
            'title' => __('Tier Commission Value & Type'),
            'disabled' => ($inStore && !$inStoreData),
            'after_element_html' => $inStore ? '</td><td class="use-default">
			<input id="tier_commission_default" name="tier_commission_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($inStoreData ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="tier_commission_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
          </td><td class="scope-label">
			[' . $scopeLabel . ']
          ' : '</td><td class="scope-label">
			[' . $scopeLabel . ']',
            ]
        )->setRenderer($this->_abstractbackendmagento->getLayout()->createBlock('Magestore\Affiliatepluslevel\Block\Adminhtml\Program\Tier')->setProgramData($data));

        $inStoreData = isset($data['use_sec_tier_in_store']) ? $data['use_sec_tier_in_store'] : false;
        $fieldset->addField(
            'use_sec_tier',
            'select',
            [
            'name' => 'use_sec_tier',
            'label' => __('Use different commission from 2nd order of a Customer'),
            'title' => __('Use different commission from 2nd order of a Customer'),
            'values' => $this->_objectManager->get('Magento\Config\Model\Config\Source\Yesno')->toOptionArray(),
            'disabled' => ($inStore && !$inStoreData),
            'after_element_html' => '<p class="note">' . __('Select "No" to apply above commission for all orders') . '</p>' .
                ($inStore ? '</td><td class="use-default">
			<input id="use_sec_tier_default" name="use_sec_tier_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($inStoreData ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="use_sec_tier_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
          </td>' : '') . '</td><td class="scope-label">
			[' . $scopeLabel . ']',
            'onchange' => 'changeSecTierConfig()'
            ]
        );

        $inStoreData = isset($data['sec_tier_commission_in_store']) ? $data['sec_tier_commission_in_store'] : false;
        $fieldset->addField(
            'sec_tier_commission',
            'text',
            [
            'name' => 'sec_tier_commission',
            'label' => __('Tier Commission Value & Type (from 2nd order)'),
            'title' => __('Tier Commission Value & Type (from 2nd order)'),
            'disabled' => ($inStore && !$inStoreData),
            'after_element_html' => $inStore ? '</td><td class="use-default">
			<input id="sec_tier_commission_default" name="sec_tier_commission_default" type="checkbox" value="1" class="checkbox config-inherit" ' . ($inStoreData ? '' : 'checked="checked"') . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" />
			<label for="sec_tier_commission_default" class="inherit" title="' . $defaultTitle . '">' . $defaultLabel . '</label>
          </td><td class="scope-label">
			[' . $scopeLabel . ']
          ' : '</td><td class="scope-label">
			[' . $scopeLabel . ']',
            ]
        )->setRenderer($this->_abstractbackendmagento->getLayout()->createBlock('Magestore\Affiliatepluslevel\Block\Adminhtml\Program\Sectier')->setProgramData($data));
    }
}