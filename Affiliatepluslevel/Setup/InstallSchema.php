<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Affiliatepluslevel
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Affiliatepluslevel\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * schema table
     */
    const SCHEMA_LEVEL_TIER =           'magestore_affiliatepluslevel_tier';
    const SCHEMA_LEVEL_TRANSACTION =    'magestore_affiliatepluslevel_transaction';

    const SCHEMA_ACCOUNT =              'magestore_affiliateplus_account';
    const SCHEMA_TRANSACTION =          'magestore_affiliateplus_transaction';


    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $installer->getConnection()->dropTable($installer->getTable(self::SCHEMA_LEVEL_TIER));
        $installer->getConnection()->dropTable($installer->getTable(self::SCHEMA_LEVEL_TRANSACTION));

        /**
         * Create table 'magestore_affiliatepluslevel_tier'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::SCHEMA_LEVEL_TIER))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'tier_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'Tier Id'
            )->addColumn(
                'toptier_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'Top Tier Id'
            )->addColumn(
                'level',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'Level'
            )->addIndex(
                $installer->getIdxName(self::SCHEMA_LEVEL_TIER, ['tier_id']),
                ['tier_id']
            )->addIndex(
                $installer->getIdxName(self::SCHEMA_LEVEL_TIER, ['toptier_id']),
                ['toptier_id']
            )->addForeignKey(
                $installer->getFkName(
                    self::SCHEMA_LEVEL_TIER,
                    'tier_id',
                    self::SCHEMA_ACCOUNT,
                    'account_id'
                ),
                'tier_id',
                $installer->getTable(self::SCHEMA_ACCOUNT),
                'account_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    self::SCHEMA_LEVEL_TIER,
                    'toptier_id',
                    self::SCHEMA_ACCOUNT,
                    'account_id'
                ),
                'toptier_id',
                $installer->getTable(self::SCHEMA_ACCOUNT),
                'account_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Affiliatepluslevel Tier table');
        $installer->getConnection()->createTable($table);

        /**
         * End create table 'magestore_affiliatepluslevel_tier'
         */

        /**
         * Create table 'magestore_affiliatepluslevel_transaction'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::SCHEMA_LEVEL_TRANSACTION))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'tier_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'Tier Id'
            )->addColumn(
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'Transaction Id'
            )->addColumn(
                'level',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'Level'
            )->addColumn(
                'commission',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Commission'
            )->addColumn(
                'commission_plus',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Commission plus'
            )->addIndex(
                $installer->getIdxName(self::SCHEMA_LEVEL_TRANSACTION, ['tier_id']),
                ['tier_id']
            )->addIndex(
                $installer->getIdxName(self::SCHEMA_LEVEL_TRANSACTION, ['transaction_id']),
                ['transaction_id']
            )->addForeignKey(
                $installer->getFkName(
                    self::SCHEMA_LEVEL_TRANSACTION,
                    'tier_id',
                    self::SCHEMA_ACCOUNT,
                    'account_id'
                ),
                'tier_id',
                $installer->getTable(self::SCHEMA_ACCOUNT),
                'account_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    self::SCHEMA_LEVEL_TRANSACTION,
                    'transaction_id',
                    self::SCHEMA_TRANSACTION,
                    'transaction_id'
                ),
                'transaction_id',
                $installer->getTable(self::SCHEMA_TRANSACTION),
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Affiliatepluslevel Transaction table');
        $installer->getConnection()->createTable($table);

        /**
         * End create table 'magestore_affiliatepluslevel_transaction'
         */

        /**
         * Convert from old configuration to new configuration fields
         */
        /*$movingPre = 'affiliateplus/';
        $movingMap = array(
            'multilevel/max_level'              => 'commission/max_level',
            'multilevel/tier_commission'        => 'commission/tier_commission',

            'multilevel/is_sent_email_account_new_transaction'  => 'email/multilevel_is_sent_email_account_new_transaction',
            'multilevel/is_sent_email_account_updated_transaction'  => 'email/multilevel_is_sent_email_account_updated_transaction',
            'multilevel/new_transaction_account_email_template' => 'email/multilevel_new_transaction_account_email_template',
            'multilevel/updated_transaction_account_email_template' => 'email/multilevel_updated_transaction_account_email_template',
        );*/

        /*
        $movingSql = '';
        foreach ($movingMap as $moveFrom => $moveTo) {
            $movingSql .= "UPDATE {$this->getTable('core/config_data')} ";
            $movingSql .= "SET path = '" . $movingPre . $moveTo . "' ";
            $movingSql .= "WHERE path = '" . $movingPre . $moveFrom . "'; ";
        }
        $installer->run($movingSql);
        */

        $installer->endSetup();
    }

}
