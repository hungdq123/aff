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
class Sectier extends \Magestore\Affiliatepluslevel\Block\Adminhtml\Program\Tier
{
    public function getHtmlId(){
        return 'grid_sec_tier_commission';
    }

    public function getDefaultCommission(){
        $data = $this->getProgramData();
        if (isset($data['sec_commission']) && $data['sec_commission']) {
            return isset($data['secondary_commission']) ? $data['secondary_commission'] : 0;
        }
        return isset($data['commission']) ? $data['commission'] : 0;
    }

    public function getDefaultCommissionType(){
        $data = $this->getProgramData();
        if (isset($data['sec_commission']) && $data['sec_commission']) {
            return isset($data['sec_commission_type']) ? $data['sec_commission_type'] : 'percentage';
        }
        return isset($data['commission_type']) ? $data['commission_type'] : 'percentage';
    }
}