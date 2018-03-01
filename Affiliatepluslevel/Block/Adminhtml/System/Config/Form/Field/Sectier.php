<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 19/04/2017
 * Time: 16:01
 */
namespace Magestore\Affiliatepluslevel\Block\Adminhtml\System\Config\Form\Field;

class Sectier extends \Magestore\Affiliatepluslevel\Block\Adminhtml\System\Config\Form\Field\Tier
{
    public function getHtmlId(){
        return 'affiliateplus_commission_sec_tier_commission';
    }

    public function getDefaultCommission(){
        if ($this->_getConfig('affiliateplus/commission/use_secondary')) {
            return $this->_getConfig('affiliateplus/commission/secondary_commission');
        }
        return $this->_getConfig('affiliateplus/commission/commission_value');
    }

    public function getDefaultCommissionType(){
        if ($this->_getConfig('affiliateplus/commission/use_secondary')) {
            return $this->_getConfig('affiliateplus/commission/secondary_type');
        }
        return $this->_getConfig('affiliateplus/commission/commission_type');
    }
}