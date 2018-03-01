<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 24/04/2017
 * Time: 10:07
 */

namespace Magestore\Affiliatepluslevel\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magestore\Affiliatepluslevel\Observer\AbtractObserver;

class AddFieldToAccountFieldset extends AbtractObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_helperTier->isPluginEnabled()) {
            return $this;
        }

        $form = $observer->getForm();
        $fieldset = $observer->getFieldset();
        $loadData = $observer->getLoadData();
        $data = array();
        if (!empty($loadData) && !empty($loadData['account_id'])) {
            $tier = $this->_tierCollectionFactory->create()
                ->addFieldToFilter('tier_id', $loadData['account_id'])
                ->getFirstItem();
            $data['level'] = $tier->getLevel();
            $data['toptier_id'] = $tier->getToptierId();
            $data['toptier'] = $this->_accountFactory->create()->load($data['toptier_id'])->getName();
        }
        if(1){
        $fieldset->addField('toptier', 'text', array(
            'label' => __('Upper tier'),
            'name' => 'toptier',
            'min-width' => '200px',
            'style' => 'background:none; border:1px solid #adadad; margin-bottom:10px;',
            'readonly' => true,
            'after_element_html' => '<div class="label" style="background: none !important; width: 52px; border-left: 1px solid #adadad;"><a href="javascript:showSelectTopTier()" title="'
                . __('Change') . '" id="type_id_rotator_banners">'
                . __('Change') . '</a></div>'
                . '<script type="text/javascript">
                    require([
                          \'prototype\',
                          \'jquery\',
                          \'Magestore_Affiliatepluslevel/js/tinybox/tinybox\'
                        ], function($){
                            showSelectTopTier = function () {
                                new Ajax.Request("' .$this->_helperBackend->getUrl('affiliateplusadmin/account/toptier', array('_current' => true)) . '", {
                                    parameters: {form_key: FORM_KEY, map_toptier_id: jQuery(\'#account_toptier_id\').val() || 0},
                                    evalScripts: true,
                                    onSuccess: function(transport) {
                                        TINY.box.show("");
                                        $$("#tinycontent")[0].update(transport.responseText);
                                    }
                                });
                            }
                            
                        });                  
                </script>',

        ));

        $fieldset->addField('level', 'text', array(
            'label' => __('Level'),
            'name' => 'level',
            'readonly' => true,
            'style'    => 'background:none;',
            'note' => __('Depending on upper tier\'s level'),
        ));

        $fieldset->addField('toptier_id', 'hidden', array(
            'name' => 'toptier_id',
        ));

        $form->addValues($data);
    }
    }
}