<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 10:05
 */
namespace Magestore\Affiliatepluslevel\Model\ResourceModel;

/**
 * Class Account
 * @package Magestore\Affiliateplusprogram\Model\ResourceModel
 */
class Tier extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Magestore\Affiliatepluslevel\Setup\InstallSchema::SCHEMA_LEVEL_TIER,'id');
    }
}