<?php
/**
 * Created by PhpStorm.
 * User: zeus
 * Date: 18/04/2017
 * Time: 09:59
 */
namespace Magestore\Affiliatepluslevel\Model;

/**
 * Class Program
 * @package Magestore\Affiliatepluslevel\Model
 */
class Tier extends AbstractModel
{

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magestore\Affiliatepluslevel\Model\ResourceModel\Tier');
    }
}