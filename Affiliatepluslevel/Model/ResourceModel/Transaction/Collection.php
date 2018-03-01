<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 10:11
 */
namespace Magestore\Affiliatepluslevel\Model\ResourceModel\Transaction;

/**
 * Class Collection
 * @package Magestore\Affiliateplusprogram\Model\ResourceModel\Program
 */
class Collection extends \Magestore\Affiliatepluslevel\Model\ResourceModel\AbstractCollection
{


    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Magestore\Affiliatepluslevel\Model\Transaction', 'Magestore\Affiliatepluslevel\Model\ResourceModel\Transaction');
    }
}