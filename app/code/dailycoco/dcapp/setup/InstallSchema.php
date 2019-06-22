<?php
namespace Dailycoco\Dcapp\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

	public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();
		if (!$installer->tableExists('dailycoco_dcapp_product_delivery')) {

			$table = $installer->getConnection()->newTable(
				$installer->getTable('dailycoco_dcapp_product_delivery')
			)->addColumn(
					'order_delivery_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Order Delivery ID'
				)
				->addColumn(
					'order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'nullable' => false,
						'unsigned' => true,
					],
					'Order ID'
				)
				->addColumn(
					'delivery_date',
					\Magento\Framework\DB\Ddl\Table::TYPE_DATE,
					null,
					['nullable => false'],
					'Order Delivery Date'
				)->addColumn(
					'delivery_status',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Daily Delivery Status'
				);


			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('dailycoco_Dcapp_product_delivery'),
				$setup->getIdxName(
					$installer->getTable('dailycoco_Dcapp_product_delivery'),
					['delivery_date','delivery_status'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['delivery_date','delivery_status'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}
		$installer->endSetup();
	}
}