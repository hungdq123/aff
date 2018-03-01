<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 10:06
 */
namespace Magestore\Affiliatepluslevel\Model\ResourceModel;

/**
 * Class Account
 * @package Magestore\Affiliateplusprogram\Model\ResourceModel
 */
class Transaction extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Magestore\Affiliatepluslevel\Setup\InstallSchema::SCHEMA_LEVEL_TRANSACTION,'id');
    }
}